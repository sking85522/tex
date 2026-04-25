<?php
namespace Core\NLP;

require_once __DIR__ . '/TextCleaner.php';
require_once __DIR__ . '/InputNormalizer.php';
require_once __DIR__ . '/LanguageDetector.php';
require_once __DIR__ . '/Tokenizer.php';
require_once __DIR__ . '/StopWords.php';
require_once __DIR__ . '/Stemmer.php';

class NLPPipeline {
    private TextCleaner $cleaner;
    private InputNormalizer $normalizer;
    private LanguageDetector $detector;
    private Tokenizer $tokenizer;
    private StopWords $stopWords;
    private Stemmer $stemmer;
    
    // Metadata about the last processed text
    private array $meta = [];

    public function __construct() {
        $this->cleaner = new TextCleaner();
        $this->normalizer = new InputNormalizer();
        $this->detector = new LanguageDetector();
        $this->tokenizer = new Tokenizer();
        $this->stopWords = new StopWords();
        $this->stemmer = new Stemmer();
    }

    /**
     * Complete NLP Flow: 
     * Clean -> Normalize -> Detect -> Tokenize -> Filter -> Stem -> Bigrams
     */
    public function process(string $text): string {
        // 1. Cleaning (Slang & Punctuation)
        $text = $this->cleaner->clean($text);
        
        // 2. Normalization (Unicode & Numbers)
        $text = $this->normalizer->normalize($text);
        $text = $this->normalizer->normalizeNumbers($text);

        // 3. Language Detection
        $this->meta['lang'] = $this->detector->detect($text);
        
        // 4. Tokenization (Modular preference)
        if (class_exists('NLPHP\NLPHP')) {
            $tokens = \NLPHP\NLPHP::word_tokenize($text);
        } else {
            $tokens = $this->tokenizer->tokenize($text);
        }
        $this->meta['tokens'] = $tokens;
        
        // 5. Remove Stopwords
        $filtered = $this->stopWords->remove($tokens);
        
        // 6. Stemming
        $stemmed = $this->stemmer->stemAll($filtered);
        
        // 7. Generate Bi-grams (Modular preference)
        if (class_exists('NLPHP\NLPHP')) {
            $bigrams = \NLPHP\NLPHP::bigrams($text);
            // Clean bigrams to match core format (token1_token2)
            $bigrams = array_map(fn($b) => str_replace(' ', '_', $b), $bigrams);
        } else {
            $bigrams = $this->generateBigrams($stemmed);
        }
        $this->meta['bigrams'] = $bigrams;
        
        // Combine features
        $allFeatures = array_unique(array_merge($stemmed, $bigrams));
        
        return implode(' ', $allFeatures);
    }

    public function getLastMetadata(): array {
        return $this->meta;
    }

    private function generateBigrams(array $tokens): array {
        $bigrams = [];
        $count = count($tokens);
        for ($i = 0; $i < $count - 1; $i++) {
            $bigrams[] = $tokens[$i] . '_' . $tokens[$i+1];
        }
        return $bigrams;
    }
}
