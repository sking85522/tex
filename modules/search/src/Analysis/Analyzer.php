<?php

namespace SearchPHP\Analysis;

class Analyzer
{
    private $stopWords = [
        'a', 'about', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for', 'from', 'how', 'i', 'in', 'is', 'it',
        'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'where', 'who', 'will', 'with'
    ];

    public function analyze(string $text): array
    {
        // Lowercase
        $text = strtolower($text);

        // Remove punctuation and special characters, keep only alphanumerics and spaces
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);

        // Tokenize by splitting on whitespace
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Filter out stop words and short tokens
        $filtered = array_filter($tokens, function($token) {
            return strlen($token) > 1 && !in_array($token, $this->stopWords);
        });

        // Stemming could be added here (e.g., Porter Stemmer), but we will keep it simple for now
        // Or simple suffix stripping (very basic)
        $stemmed = array_map(function($token) {
            if (substr($token, -3) === 'ing') return substr($token, 0, -3);
            if (substr($token, -2) === 'es' && strlen($token) > 4) return substr($token, 0, -2);
            if (substr($token, -1) === 's' && strlen($token) > 3 && substr($token, -2) !== 'ss') return substr($token, 0, -1);
            return $token;
        }, $filtered);

        return array_values($stemmed);
    }
}
