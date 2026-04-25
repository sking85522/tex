<?php
namespace Core\Tools\Search;

/**
 * GoogleSearchTool
 * Pro-level web searching without API keys using optimized scraping.
 */
class GoogleSearchTool {

    public function run($params = []) {
        $query = $params['prompt'] ?? ($params[0] ?? '');
        if (empty($query)) return "Please specify what you want me to search for, Sir.";

        $url = "https://www.google.com/search?q=" . urlencode($query) . "&num=5";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36");
        
        $html = curl_exec($ch);
        curl_close($ch);

        if (!$html) return "Sir, internet connectivity neural link down hai. Please check connection.";

        // Extract snippets
        preg_match_all('/<div class="BNeawe s3v9rd AP7Wnd"><div><div><div class="BNeawe s3v9rd AP7Wnd">(.*?)<\/div>/', $html, $matches);
        
        if (empty($matches[1])) {
            // Fallback selector
            preg_match_all('/<div class="v7W49e">.*?<div class="VwiC3b yXK7lf MUY17e yDq4W"><span>(.*?)<\/span>/', $html, $matches);
        }

        $results = array_slice($matches[1], 0, 3);
        $results = array_map('strip_tags', $results);

        if (empty($results)) {
            return "Sir, Google ne koi direct result nahi diya, par main analysis continue kar raha hoon.";
        }

        return "Sir, Google (Pro Search Engine) se mujhe ye jaankari mili hai:\n\n" . implode("\n\n---\n\n", $results);
    }
}
