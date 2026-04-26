<?php
$signal_file = 'brain/core/Engine/SignalProcessor.php';
$content = file_get_contents($signal_file);

$sales_method = "
    public function isSalesConsultationPrompt(string \$input): bool {
        \$x = mb_strtolower(trim(\$input), 'UTF-8');
        \$signals = [
            'website banwana', 'app banwana', 'software banwana', 'develop a website',
            'create an app', 'cost for app', 'cost for website', 'price for software',
            'hire a developer', 'need a developer', 'project deal', 'mera project',
            'mujhe ek app', 'mujhe ek website', 'e-commerce site', 'ecommerce site',
            'business website', 'tech stack', 'client deal'
        ];
        foreach (\$signals as \$signal) {
            if (str_contains(\$x, \$signal)) return true;
        }
        return false;
    }
";

// Insert the new method before the last closing brace
$content = preg_replace("/\}\s*$/", $sales_method . "\n}", $content);

// Also restore the isLikelyCodePrompt fix that was lost during git restore earlier
$replacementCode = "    public function isLikelyCodePrompt(string \$input): bool {
        \$x = mb_strtolower(\$input, 'UTF-8');
        \$signals = ['code', 'coding', 'php', 'javascript', 'js', 'html', 'css', 'sql', 'api', 'function', 'class', 'bug', 'debug', 'react', 'python', 'script', 'program', 'snippet'];
        foreach (\$signals as \$signal) {
            if (str_contains(\$x, \$signal)) return true;
        }
        return false;
    }";
$content = preg_replace("/public function isLikelyCodePrompt.*?return false;\n    \}/s", $replacementCode, $content);

file_put_contents($signal_file, $content);
