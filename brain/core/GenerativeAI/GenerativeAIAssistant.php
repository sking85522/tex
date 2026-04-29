<?php
namespace Core\GenerativeAI;

require_once __DIR__ . '/MarkovGenerator.php';
require_once __DIR__ . '/Transformers.php';

class GenerativeAIAssistant {
    private MarkovGenerator $markov;
    private Transformers $transformer;

    public function __construct() {
        $this->markov = new MarkovGenerator();
        $this->transformer = new Transformers();
        
        // NO HARDCODED KNOWLEDGE. NO LOGIC LOADING.
        // AI starts with a 100% clean slate.
    }


    public function generateThought(): string {
        $thought = $this->markov->generate();
        if ($thought === '' || str_contains($thought, 'not learned enough')) {
            $fallbacks = [
                "I am analyzing your request through my neural pathways, but I need a bit more context. Could you elaborate?",
                "That is an interesting technical concept. As an independent AI, I am continuously learning. Please provide more details.",
                "My internal algorithms are processing this. Tech Elevate X designed me to handle complex tasks, what specific outcome are you looking for?"
            ];
            return $fallbacks[array_rand($fallbacks)];
        }
        return $thought;
    }


    public function learn(string $text): void {
        $this->markov->train($text);
    }
}
