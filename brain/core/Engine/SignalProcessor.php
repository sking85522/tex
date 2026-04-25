<?php
namespace Core\Engine;

class SignalProcessor {
    public function isLikelyCodePrompt(string $input): bool {
        $x = mb_strtolower($input, 'UTF-8');
        $signals = ['code', 'coding', 'php', 'javascript', 'html', 'css', 'sql', 'api', 'function', 'class', 'bug', 'debug'];
        foreach ($signals as $signal) {
            if (str_contains($x, $signal)) return true;
        }
        return false;
    }

    public function isLikelyWebKnowledgePrompt(string $input): bool {
        $x = mb_strtolower($input, 'UTF-8');
        if (in_array(trim($x), ['next', 'continue', 'agla', 'aage', 'php', 'java', 'javascript', 'js', 'json'], true)) return false;
        
        if (preg_match('/capi[a-z]*\s+of\s+[a-z\.\- ]{2,60}/iu', $x) === 1 || preg_match('/[a-z\.\- ]{2,60}\s+capi[a-z]*/iu', $x) === 1) return true;
        
        return str_contains($x, 'what is')
            || str_contains($x, '?')
            || str_contains($x, 'who is')
            || str_contains($x, 'where is')
            || str_contains($x, 'tell me about')
            || str_starts_with($x, 'about ')
            || str_contains($x, ' ke baare me');
    }

    public function isTeachMePrompt(string $input): bool {
        $x = mb_strtolower(trim($input), 'UTF-8');
        return str_contains($x, 'mujhse siko')
            || str_contains($x, 'mujse siko')
            || str_contains($x, 'mujhse seekho')
            || str_contains($x, 'mujse seekho')
            || str_contains($x, 'learn from me');
    }

    public function isCasualPrompt(string $input): bool {
        $x = mb_strtolower(trim($input), 'UTF-8');
        if ($x === '') return true;
        $casual = [
            'tum kese ho', 'tum kaise ho', 'kaise ho', 'kya haal h', 'kya haal hai',
            'kya kya kr skte ho', 'kya kar sakte ho', 'kya tuko mujese kuch sikhna h',
            'mujese kuch sikhna', 'ek joke', 'joke', 'google', 'next'
        ];
        foreach ($casual as $c) {
            if (str_contains($x, $c)) return true;
        }
        return false;
    }

    public function extractMemoryFact(string $input): ?array {
        $patterns = [
            '/my name is\s+(.+)$/i' => 'name',
            '/mera naam\s+(.+)$/i' => 'name',
            '/my friend name is\s+(.+)$/i' => 'friend_name',
            '/mere friend ka naam\s+(.+)$/i' => 'friend_name',
            '/i live in\s+(.+)$/i' => 'city',
            '/main rehta hun\s+(.+)$/i' => 'city',
            '/main rehti hun\s+(.+)$/i' => 'city',
            '/remember (?:that|this)\s+(.+?)\s+is\s+(.+)$/i' => null,
            '/yaad rakho\s+(.+?)\s+(.+)$/i' => null,
        ];

        foreach ($patterns as $regex => $fixedKey) {
            if (preg_match($regex, trim($input), $m) !== 1) continue;
            if ($fixedKey !== null) {
                $value = trim((string) $m[1]);
                $lv = mb_strtolower($value, 'UTF-8');
                if (str_contains($lv, 'kya') || str_contains($lv, '?') || str_contains($lv, 'what')) return null;
                return ['key' => $fixedKey, 'value' => $value];
            }
            if (count($m) >= 3) return ['key' => trim($m[1]), 'value' => trim($m[2])];
        }
        return null;
    }

    public function isQuizContinuationPrompt(string $input, array $recent): bool {
        $x = mb_strtolower(trim($input), 'UTF-8');
        if (!in_array($x, ['next', 'continue', 'next quiz', 'agla', 'aage'], true)) return false;
        $last = end($recent);
        return is_array($last) && (($last['intent'] ?? '') === 'quiz_request' || str_contains((string) ($last['content'] ?? ''), 'Quiz'));
    }
}
