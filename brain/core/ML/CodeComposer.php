<?php
namespace Core\ML;

class CodeComposer
{
    public function compose(string $input, string $languageMode): array
    {
        $text = mb_strtolower(trim($input), 'UTF-8');

        if ($this->has($text, ['index.html', 'html code', 'what is html', 'write html'])) {
            return ['intent' => 'code_generate', 'title' => 'HTML Starter (index.html)', 'body' => $this->htmlStarterSnippet()];
        }
        if ($this->has($text, ['write php', 'php code', 'php function'])) {
            return ['intent' => 'code_generate', 'title' => 'PHP Starter Function', 'body' => $this->phpStarterSnippet()];
        }
        if ($this->has($text, ['javascript code', 'js code', 'write javascript'])) {
            return ['intent' => 'code_generate', 'title' => 'JavaScript Utility Function', 'body' => $this->jsSnippet()];
        }
        return ['intent' => 'code_generate', 'title' => 'Starter Function (JavaScript)', 'body' => $this->jsSnippet()];
    }

    private function has(string $text, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (mb_stripos($text, $needle, 0, 'UTF-8') !== false) return true;
        }
        return false;
    }

    private function htmlStarterSnippet(): string
    {
        return "```html\n<!doctype html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"utf-8\">\n  <title>My App</title>\n</head>\n<body>\n  <h1>Hello, World</h1>\n</body>\n</html>\n```";
    }

    private function phpStarterSnippet(): string
    {
        return "```php\n<?php\nfunction greetUser(string \$name): string\n{\n    return 'Hello, ' . ucfirst(trim(\$name)) . '!';\n}\n```";
    }

    private function jsSnippet(): string
    {
        return "```javascript\nfunction groupBy(items, keyFn) {\n  return items.reduce((acc, item) => {\n    const key = keyFn(item);\n    if (!acc[key]) acc[key] = [];\n    acc[key].push(item);\n    return acc;\n  }, {});\n}\n```";
    }
}
