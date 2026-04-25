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
        return $this->markov->generate();
    }

    public function learn(string $text): void {
        $this->markov->train($text);
    }
}
