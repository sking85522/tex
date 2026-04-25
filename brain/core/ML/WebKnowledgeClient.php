<?php
namespace Core\ML;

class WebKnowledgeClient
{
    private int $timeoutMs;
    private bool $enabled;
    private ?ShellHttpAdapter $shellAdapter;

    public function __construct(bool $enabled = true, int $timeoutMs = 5000)
    {
        $this->enabled = $enabled;
        $this->timeoutMs = max(1000, $timeoutMs);
        $this->shellAdapter = new ShellHttpAdapter();
    }

    public function answerCapitalQuery(string $query): ?string
    {
        $country = $this->extractCountryFromCapitalQuery($query);
        if ($country === null) {
            return null;
        }

        $known = $this->knownCapital($country);
        if ($known !== null) {
            return $known;
        }

        if (!$this->enabled) {
            return null;
        }

        $url = 'https://restcountries.com/v3.1/name/' . rawurlencode($country) . '?fields=name,capital';
        $json = $this->getJson($url);
        if (!is_array($json) || !$json) {
            return null;
        }

        $row = $json[0] ?? null;
        if (!is_array($row)) {
            return null;
        }
        $capital = $row['capital'][0] ?? null;
        $name = $row['name']['common'] ?? $country;
        if (!is_string($capital) || $capital === '') {
            return null;
        }
        return 'Capital of ' . $name . ' is ' . $capital . '.';
    }

    public function answerWebSnippet(string $query): ?string
    {
        if (!$this->enabled) {
            return null;
        }

        $q = trim($query);
        if ($q === '') {
            return null;
        }

        $ddg = $this->fromDuckDuckGo($q);
        if ($ddg !== null) return $ddg;

        $wiki = $this->fromWikipedia($q);
        if ($wiki !== null) return $wiki;

        return null;
    }

    private function fromDuckDuckGo(string $query): ?string
    {
        $url = 'https://api.duckduckgo.com/?q=' . rawurlencode($query) . '&format=json&no_html=1&skip_disambig=1';
        $json = $this->getJson($url);
        if (!is_array($json)) return null;

        $abstract = trim((string) ($json['AbstractText'] ?? ''));
        if ($abstract !== '') return $this->withSource($abstract, 'DuckDuckGo');

        return null;
    }

    private function fromWikipedia(string $query): ?string
    {
        $searchUrl = 'https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=' . rawurlencode($query) . '&utf8=1&format=json';
        $search = $this->getJson($searchUrl);
        if (!is_array($search)) return null;
        $title = $search['query']['search'][0]['title'] ?? null;
        if (!$title) return null;

        $summaryUrl = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($title);
        $summary = $this->getJson($summaryUrl);
        if (!is_array($summary)) return null;
        $extract = trim((string) ($summary['extract'] ?? ''));
        if ($extract === '') return null;
        return $this->withSource($extract, 'Wikipedia');
    }

    private function extractCountryFromCapitalQuery(string $query): ?string
    {
        $q = mb_strtolower(trim($query), 'UTF-8');
        if (preg_match('/capi[a-z]*\s+of\s+([a-z\.\- ]{2,60})/iu', $q, $m) === 1) return $this->normalizePlace((string) $m[1]);
        if (preg_match('/(.+)\s+की\s+राजधानी/u', $query, $m) === 1) return $this->normalizePlace((string) $m[1]);
        return null;
    }

    private function knownCapital(string $country): ?string
    {
        $map = [
            'india' => 'Capital of India is New Delhi.',
            'bharat' => 'Capital of India is New Delhi.',
            'usa' => 'Capital of USA is Washington, D.C.',
            'japan' => 'Capital of Japan is Tokyo.',
        ];
        $key = mb_strtolower(trim($country), 'UTF-8');
        return $map[$key] ?? null;
    }

    private function normalizePlace(string $place): string
    {
        return trim(mb_strtolower($place, 'UTF-8'), " \t\n\r\0\x0B,.-");
    }

    private function getJson(string $url): ?array
    {
        $ctx = stream_context_create(['http' => ['timeout' => $this->timeoutMs / 1000, 'header' => "Accept: application/json\r\nUser-Agent: JyotiAI/0.0.4\r\n"]]);
        $raw = @file_get_contents($url, false, $ctx);
        if (!is_string($raw) || $raw === '') {
            return $this->shellAdapter->getJson($url, $this->timeoutMs);
        }
        return json_decode($raw, true);
    }

    private function withSource(string $text, string $source): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
        if (mb_strlen($text, 'UTF-8') > 320) $text = mb_substr($text, 0, 320, 'UTF-8') . '...';
        return $text . " [source: {$source}]";
    }
}
