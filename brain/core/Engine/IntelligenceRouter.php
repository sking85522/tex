<?php
namespace Core\Engine;

/**
 * HRITIK AI - INTELLIGENCE ROUTER
 * Handles NLP, Intent Detection, and NLU Deep Understanding.
 */
class IntelligenceRouter {
    private $nlp;
    private $intentDetector;
    private $intentNormalizer;
    private $classifier;
    private $nlu;

    public function __construct($nlp, $intentDetector, $intentNormalizer, $classifier, $nlu) {
        $this->nlp = $nlp;
        $this->intentDetector = $intentDetector;
        $this->intentNormalizer = $intentNormalizer;
        $this->classifier = $classifier;
        $this->nlu = $nlu;
    }

    public function analyze(string $prompt): array {
        // NLP Processing
        $processed = $this->nlp->process($prompt);
        
        // Intent Detection
        $intent = $this->intentDetector->detect($prompt);
        if ($intent === 'unknown') {
            $intent = $this->classifier->predict($processed);
        }
        $intent = $this->intentNormalizer->normalize($intent);

        // NLU Understanding
        $nluResult = $this->nlu->understand($prompt, $intent);
        
        return [
            'intent' => $nluResult['intent'] ?? $intent,
            'processed_prompt' => $processed,
            'nlu_result' => $nluResult
        ];
    }
}
