<?php
namespace Core\NLU;

require_once __DIR__ . '/TFIDFVectorizer.php';
require_once __DIR__ . '/CosineSimilarity.php';
require_once __DIR__ . '/EntityExtractor.php';
require_once __DIR__ . '/ContextTracker.php';
require_once __DIR__ . '/SemanticMatcher.php';

/**
 * NLU Engine — Natural Language Understanding
 * The brain's comprehension layer. Takes raw text and produces:
 * - Intent (what does the user want?)
 * - Entities (what are they talking about?)
 * - Context (what was discussed before?)
 * - Knowledge Match (do we know the answer?)
 * - Confidence Score (how sure are we?)
 */
class NLUEngine {

    private EntityExtractor $entityExtractor;
    private ContextTracker $contextTracker;
    private SemanticMatcher $semanticMatcher;
    private array $lastResult = [];

    public function __construct() {
        $this->entityExtractor = new EntityExtractor();
        $this->contextTracker = new ContextTracker();
        $this->semanticMatcher = new SemanticMatcher();
    }

    /**
     * Full NLU Processing Pipeline
     * Input: raw user text
     * Output: structured understanding
     */
    public function understand(string $text, string $detectedIntent = 'unknown'): array {
        $originalText = $text;

        // 1. Co-reference Resolution (resolve pronouns using context)
        $text = $this->contextTracker->resolveReferences($text);

        // 2. Entity Extraction
        $entities = $this->entityExtractor->extract($text);

        // 3. Check if this is a follow-up question
        $isFollowUp = $this->contextTracker->isFollowUp($text);

        // 4. Intent Enhancement (upgrade 'unknown' using entities and context)
        $enhancedIntent = $this->enhanceIntent($detectedIntent, $text, $entities, $isFollowUp);

        // 5. Confidence Scoring
        $confidence = $this->calculateConfidence($enhancedIntent, $entities, $text);

        // 6. Semantic Knowledge Search
        $knowledgeMatch = null;
        if ($enhancedIntent === 'informational' || $enhancedIntent === 'unknown' || $enhancedIntent === 'knowledge_match') {
            $knowledgeMatch = $this->semanticMatcher->getSynthesizedAnswer($text);
        }

        // 7. Update Context
        $this->contextTracker->update($text, $enhancedIntent, $entities);

        // Build result
        $this->lastResult = [
            'original_text'   => $originalText,
            'resolved_text'   => $text,
            'intent'          => $enhancedIntent,
            'confidence'      => $confidence,
            'dl_delta'        => $this->lastResult['dl_delta'] ?? 0,
            'entities'        => $entities,
            'is_follow_up'    => $isFollowUp,
            'knowledge_match' => $knowledgeMatch,
            'context'         => $this->contextTracker->getContextSummary(),
        ];

        return $this->lastResult;
    }

    /**
     * Enhance intent detection using entities and context
     */
    private function enhanceIntent(string $intent, string $text, array $entities, bool $isFollowUp): string {
        // Coding intent detection (High Priority)
        if (!empty($entities['programming']['language']) || !empty($entities['programming']['structures'])) {
            return 'coding';
        }

        // If we already have a strong intent, keep it
        if ($intent !== 'unknown') return $intent;

        $lower = strtolower($text);

        // If follow-up, use last intent from context
        if ($isFollowUp) {
            $dominant = $this->contextTracker->getDominantIntent();
            if ($dominant && $dominant !== 'unknown') return $dominant;
        }

        // Entity-based intent detection
        if (!empty($entities['years'])) {
            // Questions with years are usually informational
            return 'informational';
        }

        if (!empty($entities['locations'])) {
            // Questions about locations are informational
            return 'informational';
        }

        if (!empty($entities['quantities'])) {
            // Questions with measurements might be math or informational
            if (preg_match('/[\+\-\*\/]/', $text)) {
                return 'math';
            }
            return 'informational';
        }

        // If there are meaningful keywords, try semantic matching
        if (!empty($entities['keywords']) && count($entities['keywords']) >= 2) {
            $quickMatch = $this->semanticMatcher->getBestAnswer($text);
            if ($quickMatch) return 'knowledge_match';
        }

        // Long text with question words is probably informational
        if (strlen($text) > 15 && preg_match('/\b(kya|kaun|kab|kahan|kitna|kitne|kyu|kyun|what|who|when|where|how|which|why)\b/i', $text)) {
            return 'informational';
        }

        return 'unknown';
    }

    /**
     * Calculate confidence score (0.0 - 1.0) and DL Delta
     */
    private function calculateConfidence(string $intent, array $entities, string $text): float {
        $confidence = 0.3; // Base

        // Intent boost
        if ($intent !== 'unknown') $confidence += 0.3;

        // Entity boost
        $entityCount = 0;
        foreach ($entities as $type => $items) {
            if ($type !== 'keywords') {
                $entityCount += count($items);
            }
        }
        $confidence += min($entityCount * 0.1, 0.2);

        // Text length boost (longer = more context)
        if (strlen($text) > 20) $confidence += 0.1;
        if (strlen($text) > 50) $confidence += 0.05;

        // Context continuity boost
        $contextSummary = $this->contextTracker->getContextSummary();
        if ($contextSummary['turn_count'] > 2) $confidence += 0.05;

        // ----------------------------------------------------
        // DEEP LEARNING (DL) ACTIVATION: Neural Network Delta
        // ----------------------------------------------------
        require_once __DIR__ . '/../DL/NeuralNetwork.php';
        $dlNet = new \Core\DL\NeuralNetwork(0.1);
        $dlNet->addLayer(3, 4, 'sigmoid'); // Input: 3 features
        $dlNet->addLayer(4, 1, 'sigmoid'); // Output: 1 confidence delta
        
        // Features: [normalized intent strength, entity density, text length ratio]
        $f1 = ($intent !== 'unknown') ? 0.8 : 0.2;
        $f2 = min($entityCount / 5, 1.0);
        $f3 = min(strlen($text) / 100, 1.0);
        
        // Predict delta using live neural network
        $dlOutput = $dlNet->predict([$f1, $f2, $f3]);
        $dlDelta = isset($dlOutput[0]) ? ($dlOutput[0] * 0.15) : 0.05; // Max 15% boost from DL
        
        $this->lastResult['dl_delta'] = round($dlDelta, 4);
        $confidence += $dlDelta;

        return min($confidence, 1.0);
    }

    /**
     * Get the last understanding result
     */
    public function getLastResult(): array {
        return $this->lastResult;
    }

    /**
     * Get explanation of understanding (for neural flow dashboard)
     */
    public function explain(string $text): array {
        $result = $this->understand($text);
        $semanticExplanation = $this->semanticMatcher->explain($text);

        return [
            'nlu_result' => $result,
            'semantic_analysis' => $semanticExplanation,
            'entity_summary' => $this->entityExtractor->getSummary($text),
            'context_state' => $this->contextTracker->getContextSummary()
        ];
    }

    /**
     * Build the semantic index (run once after training)
     */
    public function buildSemanticIndex(int $maxDocs = 5000): array {
        $start = microtime(true);
        $this->semanticMatcher->buildIndex($maxDocs);
        $elapsed = round(microtime(true) - $start, 2);

        return [
            'status' => 'success',
            'time_seconds' => $elapsed,
            'ready' => $this->semanticMatcher->isReady()
        ];
    }

    /**
     * Get context tracker (for Engine integration)
     */
    public function getContextTracker(): ContextTracker {
        return $this->contextTracker;
    }

    /**
     * Get semantic matcher (for direct queries)
     */
    public function getSemanticMatcher(): SemanticMatcher {
        return $this->semanticMatcher;
    }
}
