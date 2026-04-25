<?php
namespace Core\Tools\Search;

/**
 * LocalKnowledgeTool
 * Uses SearchPHP (BM25) to search through sharded knowledge.
 */
class LocalKnowledgeTool {
    
    private $knowledgeBase;

    public function __construct() {
        $this->knowledgeBase = dirname(__DIR__, 3) . '/storage/knowledge/';
    }

    public function run($params = []) {
        $query = strtolower($params['prompt'] ?? ($params[0] ?? ''));
        if (empty($query)) return "Sir, please provide a query for the knowledge base.";

        require_once dirname(__DIR__, 3) . '/modules/search/autoload.php';
        $search = new \SearchPHP\SearchPHP();

        $results = [];
        $indexFile = $this->knowledgeBase . 'logic.idx';

        if (file_exists($indexFile)) {
            try {
                $search->loadIndex($indexFile);
                $searchHits = $search->search($query, 3);
                
                foreach ($searchHits as $hit) {
                    $doc = $hit['document'];
                    $fields = $doc->getFields();
                    $results[] = $fields['answer'] ?? ($fields['context'] ?? "Match found.");
                }
            } catch (\Exception $e) {
                // Fallback to keyword scan if index fails
            }
        }

        if (empty($results)) {
            // Fallback: Keyword Scan of JSON shards
            $shards = glob($this->knowledgeBase . '*/*.json');
            foreach ($shards as $shard) {
                $data = json_decode(file_get_contents($shard), true);
                if (!$data) continue;
                foreach ($data as $item) {
                    $q = strtolower($item['q'] ?? ($item['question'] ?? ''));
                    if ($q && strpos($query, $q) !== false) {
                        $results[] = $item['a'] ?? ($item['answer'] ?? "Match found.");
                        if (count($results) >= 2) break 2;
                    }
                }
            }
        }

        if (empty($results)) return null;

        return "Sir, Hritik Knowledge Syndicate (BM25 Index) se ye information mili hai:\n\n" . implode("\n\n", $results);
    }
}
