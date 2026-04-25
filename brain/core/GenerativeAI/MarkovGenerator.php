<?php
namespace Core\GenerativeAI;

class MarkovGenerator {
    private array $chain = [];
    private array $startWords = [];

    /**
     * Train the model with a string (learning word transitions).
     */
    public function train(string $text): void {
        $words = explode(' ', strtolower(trim($text)));
        $count = count($words);

        if ($count < 2) return;

        $this->startWords[] = $words[0];

        for ($i = 0; $i < $count - 1; $i++) {
            $current = $words[$i];
            $next = $words[$i + 1];

            if (!isset($this->chain[$current])) {
                $this->chain[$current] = [];
            }
            $this->chain[$current][] = $next;
        }
    }

    /**
     * Generate a sentence from learned data.
     * @param float $temperature 0.0 to 1.0 (Higher = more random/creative)
     */
    public function generate(int $minLength = 5, int $maxLength = 15, float $temperature = 0.7): string {
        if (empty($this->startWords)) return "I have not learned enough to generate thoughts yet.";

        $word = $this->startWords[array_rand($this->startWords)];
        $sentence = [$word];

        for ($i = 0; $i < $maxLength; $i++) {
            if (!isset($this->chain[$word])) break;

            $nextWords = $this->chain[$word];
            if (empty($nextWords)) break;

            // Frequency map
            $freq = array_count_values($nextWords);
            arsort($freq);
            
            // Selection logic based on temperature
            if ($temperature < 0.3) {
                // Low temperature: pick the most common word
                $word = array_key_first($freq);
            } else {
                // Higher temperature: pick randomly from next words
                $word = $nextWords[array_rand($nextWords)];
            }

            $sentence[] = $word;

            if (count($sentence) >= $minLength && rand(0, 100) > 80) break;
        }

        return ucfirst(implode(' ', $sentence)) . ".";
    }

    public function clear(): void {
        $this->chain = [];
        $this->startWords = [];
    }
}
