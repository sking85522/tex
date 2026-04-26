<?php
$engine_file = 'brain/core/Engine.php';
$content = file_get_contents($engine_file);

// Add the sales consultant logic after the task mode section
$salesLogic = "        // 2b. Sales & Tech Consultant Mode
        if (\$this->get('signalProcessor')->isSalesConsultationPrompt(\$resolvedPrompt)) {
            require_once __DIR__ . '/Engine/SalesConsultant.php';
            \$salesConsultant = new \Core\Engine\SalesConsultant();
            \$responseContent = \$salesConsultant->handleSalesInquiry(\$resolvedPrompt);
            return ['status' => 'success', 'response' => \$responseContent, 'intent' => 'sales_deal'];
        }
";

$content = preg_replace("/\/\/ 3\. Check for System Commands/", $salesLogic . "\n        // 3. Check for System Commands", $content);

file_put_contents($engine_file, $content);
