<?php
namespace Core\NLP;

class LanguageDetector {
    
    // Key markers for Hindi/Hinglish
    private array $hiMarkers = [
        'hai', 'tha', 'thi', 'the', 'ka', 'ki', 'main', 'nahi', 'nhi', 'kyun', 'kya', 
        'karo', 'kar', 'raha', 'rahi', 'kuch', 'bhi', 'se', 'ya', 'par', 'per'
    ];

    /**
     * Detects the language of a given text.
     * Returns 'en', 'hi', or 'mixed'.
     */
    public function detect(string $text): string {
        $words = explode(' ', strtolower($text));
        $hiCount = 0;
        $enCount = 0;

        foreach ($words as $word) {
            if (in_array($word, $this->hiMarkers)) {
                $hiCount++;
            } else {
                // If it's a common English word not in Hinglish markers
                if (in_array($word, ['is', 'are', 'was', 'the', 'and', 'but', 'why', 'what'])) {
                    $enCount++;
                }
            }
        }

        if ($hiCount > 0 && $enCount > 0) return 'mixed';
        if ($hiCount > 2) return 'hi'; // High confidence in Hinglish/Hindi
        
        return 'en';
    }
}
