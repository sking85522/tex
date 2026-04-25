<?php
namespace Core\NLP;

class StopWords {
    private array $words = [
        // English
        'is', 'am', 'are', 'was', 'were', 'the', 'a', 'an', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 'can', 'will', 'just', 'should', 'now', 'it', 'its', 'they', 'them', 'their', 'we', 'us', 'our',
        // Hinglish / Hindi
        'hai', 'tha', 'thi', 'the', 'ka', 'ke', 'ki', 'main', 'ko', 'me', 'hi', 'he', 'hu', 'ho', 'se', 'ya', 'neeche', 'upar', 'liye', 'ne', 'par', 'per', 'ek', 'do', 'teen', 'aur', 'toh', 'to', 'raha', 'rahe', 'rahi', 'kuch', 'bhi', 'ab', 'jab', 'tab', 'kaun', 'kis', 'jis', 'unka', 'inka', 'isne', 'usne'
    ];

    /**
     * Removes stop words from a token array.
     */
    public function remove(array $tokens): array {
        $allWords = $this->words;
        if (class_exists('NLPHP\NLPHP')) {
            // NLPHP internal method to get stopwords (mocking since I don't see a getter, but I'll use the static method directly)
            return \NLPHP\NLPHP::remove_stopwords($tokens);
        }

        return array_values(array_filter($tokens, function($token) use ($allWords) {
            return !in_array($token, $allWords);
        }));
    }
}
