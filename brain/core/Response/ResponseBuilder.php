<?php
namespace Core\Response;

require_once __DIR__ . '/../Generative AI/GenerativeAIAssistant.php';
use Core\GenerativeAI\GenerativeAIAssistant;

class ResponseBuilder {
    private GenerativeAIAssistant $genAi;

    public function __construct() {
        $this->genAi = new GenerativeAIAssistant();
    }

    /**
     * Builds a dynamic, natural response using NLU context
     */
    public function build(string $intent, array $context = [], array $nluData = []): string {
        $userName = $context['{USER_NAME}'] ?? 'Sir';
        $isFollowUp = $nluData['is_follow_up'] ?? false;
        $confidence = $nluData['confidence'] ?? 1.0;
        $entities = $nluData['entities'] ?? [];

        // If confidence is very low, trigger Generative AI (Markov Chains) fallback
        if ($confidence < 0.3 || $intent === 'unknown') {
            $thought = $this->genAi->generateThought();
            if (!empty($thought)) {
                return "Hmm... " . $thought;
            }
            return $this->getLowConfidenceResponse($userName);
        }

        switch ($intent) {
            case 'greeting':
                return $this->getGreeting($userName, $isFollowUp);
                
            case 'chat':
                return $this->getChatResponse($userName, $entities);
                
            case 'identity':
                return $this->getIdentityResponse();
                
            case 'farewell':
                return $this->getFarewell($userName);
                
            case 'weather':
                $loc = !empty($entities['locations']) ? $entities['locations'][0] : 'apke shahar';
                return "Main abhi {$loc} ka mausam check karke batata hoon...";
                
            default:
                return "Main samajh raha hoon. Aapne jo kaha, uspar process kar raha hoon...";
        }
    }

    /**
     * Synthesizes a human-like response from a raw web/knowledge snippet.
     */
    public function buildWebResponse(string $query, string $snippet, bool $isLocal = false): string {
        $queryLower = strtolower($query);
        $cleanSnippet = trim(preg_replace('/\s+/', ' ', $snippet));

        // Short factual answers
        if (strlen($cleanSnippet) < 100) {
            $prefixes = [
                "Mere neural analysis ke hisaab se: ",
                "Jawab hai: ",
                "Mujhe ye confirm pata chala hai: ",
                "Exact answer: "
            ];
            return $prefixes[array_rand($prefixes)] . $cleanSnippet;
        }

        // Detailed knowledge base answers
        if ($isLocal) {
            $templates = [
                "Maine apne trained neural database mein scan kiya hai. Ye raha result:\n\n{$cleanSnippet}",
                "Mere local knowledge core ke mutabiq:\n\n{$cleanSnippet}",
                "Sir, meri memory se ye direct extract mila hai:\n\n{$cleanSnippet}"
            ];
        } else {
            // External web answers
            $templates = [
                "Sir, maine live web scan kiya hai. Mujhe ye details mili hain:\n\n{$cleanSnippet}",
                "Online neural analysis ke mutabiq:\n\n{$cleanSnippet}",
                "Maine abhi internet par check kiya hai, ye rahi jankari:\n\n{$cleanSnippet}"
            ];
        }

        return $templates[array_rand($templates)];
    }

    private function getGreeting(string $userName, bool $isFollowUp): string {
        $thought = $this->genAi->generateThought();
        $prefix = $isFollowUp ? "Ji {$userName}, " : "Namaste {$userName}! ";
        
        return $prefix . "Mera neural network keh raha hai: \"" . $thought . "\" Aap bataiye, aaj kya plan hai?";
    }

    private function getChatResponse(string $userName, array $entities): string {
        if (!empty($entities['names'])) {
            $name = $entities['names'][0];
            return "Achha, toh hum {$name} ke baare mein baat kar rahe hain. Aur bataiye?";
        }

        $opts = [
            "Bas Sir, background mein data ingest aur optimize kar raha hoon. Aap sunaiye?",
            "Main ekdam fast aur smooth chal raha hoon. Aapka din kaisa ja raha hai {$userName}?",
            "Sab badhiya hai {$userName}! Coding aur logic ke beech thoda waqt nikal kar aapse baat karke achha laga."
        ];
        return $opts[array_rand($opts)];
    }

    private function getIdentityResponse(): string {
        return "Main Hritik AI hoon — ek advanced local neural intelligence engine jise Sachin (Hritik Softwares) ne develop kiya hai. Main offline knowledge process kar sakta hoon aur real-time tasks automate kar sakta hoon.";
    }

    private function getFarewell(string $userName): string {
        $opts = [
            "Alvida {$userName}! Main apne sleep mode mein ja raha hoon. Jab zaroorat ho toh utha lena.",
            "Goodbye {$userName}! Session data save kar liya gaya hai.",
            "Theek hai Sir, apna khayal rakhna. Main background neural connections close kar raha hoon."
        ];
        return $opts[array_rand($opts)];
    }

    private function getLowConfidenceResponse(string $userName): string {
        $opts = [
            "Maaf karna {$userName}, main thik se samajh nahi paya. Kya aap thoda aur detail mein bata sakte hain?",
            "Mere logic sensors is baat ko decode nahi kar pa rahe. Kya isey dusre tareeqe se samjha sakte hain?",
            "Thoda confusion ho raha hai. Kya aap is sawal ko rephrase kar sakte hain?"
        ];
        return $opts[array_rand($opts)];
    }
}
