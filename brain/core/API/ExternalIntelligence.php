<?php
namespace Core\API;

class ExternalIntelligence {

    /**
     * Search free online APIs to find an answer.
     */
    public function search(string $query): string {
        // Strip out conversational fillers to get core keywords
        $cleanQuery = $this->extractKeywords($query);

        // 1. Try DuckDuckGo Instant Answer API
        $ddgAnswer = $this->queryDuckDuckGo($cleanQuery);
        if ($ddgAnswer) {
            return $ddgAnswer;
        }

        // 2. Try Wikipedia Summary API
        $wikiAnswer = $this->queryWikipedia($cleanQuery);
        if ($wikiAnswer) {
            return $wikiAnswer;
        }

        return "I searched the neural networks and online archives, but I couldn't find a definitive answer for '{$cleanQuery}'.";
    }

    private function queryWikipedia(string $query): ?string {
        $url = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . urlencode(ucwords($query));
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'HritikAI/1.0 (Integration Testing)');
        // Wikipedia will 404 if the exact page doesn't exist.
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['extract']) && !empty($data['extract'])) {
                return $data['extract'];
            }
        }
        
        return null;
    }

    private function queryDuckDuckGo(string $query): ?string {
        $url = 'https://api.duckduckgo.com/?q=' . urlencode($query) . '&format=json&no_html=1&skip_disambig=1';
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['AbstractText']) && !empty($data['AbstractText'])) {
                return $data['AbstractText'];
            }
            if (isset($data['Answer']) && !empty($data['Answer'])) {
                 return $data['Answer'];
            }
        }
        
        return null;
    }

    /**
     * Simple utility to clean prompt from chatty words
     */
    private function extractKeywords(string $prompt): string {
        $prompt = strtolower($prompt);
        $removals = ['what is', 'who is', 'tell me about', 'explain', 'what are', 'search for'];
        
        foreach ($removals as $word) {
            if (strpos($prompt, $word) === 0) {
                $prompt = substr($prompt, strlen($word));
            }
        }
        
        return trim(str_replace('?', '', $prompt));
    }
}
