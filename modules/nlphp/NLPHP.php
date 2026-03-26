<?php

namespace NLPHP;

use NLPHP\Tokenization\WordTokenizer;
use NLPHP\Tokenization\SentenceTokenizer;
use NLPHP\Corpus\Stopwords;
use NLPHP\Stemming\PorterStemmer;
use NLPHP\Classification\NaiveBayes;
use NLPHP\Chatbot\RuleBasedChatbot;

class NLPHP
{
    /**
     * Tokenize text into words.
     */
    public static function word_tokenize(string $text): array
    {
        return WordTokenizer::tokenize($text);
    }

    /**
     * Tokenize text into sentences.
     */
    public static function sent_tokenize(string $text): array
    {
        return SentenceTokenizer::tokenize($text);
    }

    /**
     * Remove English stop words from a list of words.
     */
    public static function remove_stopwords(array $words): array
    {
        return Stopwords::remove($words);
    }

    /**
     * Stem a word or array of words using Porter Stemming algorithm.
     */
    public static function stem($words)
    {
        return PorterStemmer::stem($words);
    }

    /**
     * Create a Naive Bayes Text Classifier.
     */
    public static function NaiveBayes(): NaiveBayes
    {
        return new NaiveBayes();
    }

    /**
     * Create a simple Rule-Based Chatbot.
     */
    public static function Chatbot(array $intents = []): RuleBasedChatbot
    {
        return new RuleBasedChatbot($intents);
    }
}
