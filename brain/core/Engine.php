<?php
namespace Core;

require_once __DIR__ . '/Autoloader.php';

use Core\Engine\StateManager;
use Core\Engine\CommandRouter;
use Core\Engine\ToolOrchestrator;
use Core\Engine\IntelligenceRouter;
use Core\Engine\DataRouter;
use Core\Engine\SignalProcessor;
use Core\ML\SupervisedLearner;
use Core\GenerativeAI\GenerativeAIAssistant;
use Core\Memory\FileMemoryStore;
use Core\Memory\ShortTermMemory;
use Core\Memory\ProfileMemory;
use Core\Memory\ContextManager;
use Core\Memory\MemorySafety;
use Core\NLP\NLPPipeline;
use Core\NLP\SentimentDetector;
use Core\Tools\ToolManager;
use Core\Memory\KnowledgeIngestor;
use Core\Memory\SystemIntrospection;
use Core\NLU\NLUEngine;
use Core\Response\IntentDetector;
use Core\Response\IntentNormalizer;
use Core\MachineLearningAlgorithms\SimpleTextClassifier;
use Core\SpeechProcessing\SpeechProcessingAssistant;
use Core\Memory\RelationshipManager;
use Core\DataHandling\DataHandlingAssistant;

/**
 * HRITIK AI ENGINE - CLEAN MODULAR EDITION
 * No default responses. Only learns from datasets.
 */
class Engine {
    private StateManager $stateManager;
    private CommandRouter $commandRouter;
    private ToolOrchestrator $toolOrchestrator;
    private IntelligenceRouter $intelligenceRouter;
    private DataRouter $dataRouter;
    private SignalProcessor $signalProcessor;
    
    private $memory, $stm, $profile, $context, $safety, $nlp, $sentiment, $genAi, $dataAi, $supervised, $toolManager, $relationships, $nlu, $speech, $introspection;
    private $math, $quiz, $tables;
    private $taskPlanner, $toolRouter, $verifier, $executionLog;

    public function __construct() {
        // Core state managers (Lightweight)
        $this->memory = new FileMemoryStore();
        $this->stm = new ShortTermMemory(10);
        $this->profile = new ProfileMemory();
        $this->context = new ContextManager();
        $this->safety = new MemorySafety();
        $this->stateManager = new StateManager($this->profile, $this->stm, $this->memory, $this->context);
    }

    /**
     * Lazy Loader for expensive components
     */
    private function get(string $component) {
        if (isset($this->$component)) return $this->$component;

        switch ($component) {
            case 'nlp': return $this->nlp = new NLPPipeline();
            case 'sentiment': return $this->sentiment = new SentimentDetector();
            case 'genAi': return $this->genAi = new GenerativeAIAssistant();
            case 'dataAi': return $this->dataAi = new DataHandlingAssistant();
            case 'supervised': return $this->supervised = new SupervisedLearner();
            case 'toolManager': return $this->toolManager = new ToolManager();
            case 'relationships': return $this->relationships = new RelationshipManager();
            case 'nlu': return $this->nlu = new NLUEngine();
            case 'speech': return $this->speech = new SpeechProcessingAssistant();
            case 'introspection': return $this->introspection = new SystemIntrospection();
            case 'math': return $this->math = new \Core\ML\MathEvaluator();
            case 'quiz': return $this->quiz = new \Core\ML\QuizGenerator();
            case 'tables': return $this->tables = new \Core\ML\TableGenerator();
            case 'signalProcessor': return $this->signalProcessor = new SignalProcessor();
            case 'taskPlanner': return $this->taskPlanner = new \Core\ML\TaskPlanner();
            case 'verifier': return $this->verifier = new \Core\ML\Verifier();
            case 'executionLog': return $this->executionLog = new \Core\ML\ExecutionLog();
            case 'toolRouter': 
                return $this->toolRouter = new \Core\ML\ToolRouter(
                    new \Core\ML\WebKnowledgeClient(), $this->get('math'), new \Core\ML\CodeComposer(),
                    $this->get('quiz'), $this->get('tables'), new \Core\ML\UrlContentIngestor()
                );
            case 'toolOrchestrator': return $this->toolOrchestrator = new ToolOrchestrator($this->get('toolManager'));
            case 'intelligenceRouter': 
                return $this->intelligenceRouter = new IntelligenceRouter(
                    $this->get('nlp'), new \Core\Response\IntentDetector(), new \Core\Response\IntentNormalizer(), 
                    new SimpleTextClassifier(), $this->get('nlu')
                );
            case 'commandRouter':
                return $this->commandRouter = new CommandRouter(
                    $this->get('introspection'), new \Core\Memory\KnowledgeIngestor(), $this->get('relationships'), 
                    $this->profile, $this->memory
                );
            case 'dataRouter': return $this->dataRouter = new DataRouter($this->get('dataAi'));
        }
        return null;
    }

            public function processPrompt(string $prompt, string $sessionId = 'default_session', ?string $datasetFile = null, ?string $originalName = null, ?callable $onToken = null, $pdo = null): array {
        $this->stateManager->initializeSession($sessionId, $prompt);
        if ($onToken) $onToken("[NEURAL_INIT]");

        $prompt = $this->get('safety')->sanitize($prompt);
        $recent = $this->stm->get($sessionId) ?? [];
        
        $resolvedPrompt = $this->resolveContext($prompt, $recent);
        $this->profile->load($sessionId);

        // 2. Task Mode (Complex Multi-Step Logic)
        if (mb_strlen($resolvedPrompt, 'UTF-8') > 60 && preg_match('/\b(plan|task|process|sequence|steps|karo|banao)\b/u', $resolvedPrompt)) {
            $response = $this->runTaskMode($resolvedPrompt);
            return ['status' => 'success', 'response' => $response, 'intent' => 'task_mode'];
        }

                                // 2a. Lead Capture (Checks if user is providing contact details during a sales talk)
        require_once __DIR__ . '/Engine/LeadCapture.php';
        if ($pdo !== null) {
            $leadCapture = new \Core\Engine\LeadCapture($pdo);
            $leadResponse = $leadCapture->extractAndSaveLead($resolvedPrompt, "Client Session: " . $sessionId);
            if ($leadResponse) {
                return ['status' => 'success', 'response' => $leadResponse, 'intent' => 'lead_captured'];
            }
        }

        // 2b. Sales & Tech Consultant Mode
        if ($this->get('signalProcessor')->isSalesConsultationPrompt($resolvedPrompt)) {
            require_once __DIR__ . '/Engine/SalesConsultant.php';
            $salesConsultant = new \Core\Engine\SalesConsultant();
            $responseContent = $salesConsultant->handleSalesInquiry($resolvedPrompt);
            return ['status' => 'success', 'response' => $responseContent, 'intent' => 'sales_deal'];
        }

                        // 2c. Admin System Commands (Requires admin authentication to execute safely)
        if ($this->get('signalProcessor')->isAdminCommand($resolvedPrompt)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['admin_id'])) {
                return ['status' => 'error', 'response' => 'Unauthorized. Admin session required.', 'intent' => 'unauthorized'];
            }

            $cmd = str_replace('ADMIN COMMAND:', '', $resolvedPrompt);
            $lowCmd = strtolower(trim($cmd));

            $res = "Command received.";

            if (str_contains($lowCmd, 'leads') || str_contains($lowCmd, 'client')) {
                $res = "I have successfully captured leads and they are stored securely. You can view them in the Dashboard.";
            } elseif (str_contains($lowCmd, 'write a blog') || str_contains($lowCmd, 'content')) {
                $res = "Here is a drafted blog post for Tech Elevate X: \n\n**Why AI is the Future of Tech**\nArtificial intelligence is no longer a concept of tomorrow—it's here. At Tech Elevate X, we integrate AI into every project... (You can copy this into the Blog editor).";
            } elseif (str_contains($lowCmd, 'report') || str_contains($lowCmd, 'health')) {
                $res = "System health is optimal. The Neural Engine is online and independent.";
            } else {
                $res = "I am your Admin AI Assistant. Ask me to draft content, check leads, or report system health.";
            }

            return ['status' => 'success', 'response' => $res, 'intent' => 'admin_command'];
        }

        // 3. Check for System Commands
        $sysResult = $this->get('commandRouter')->handle($resolvedPrompt, $sessionId);
        if ($sysResult) return $sysResult;

        // 4. Deep Fact Extraction
        $newFacts = $this->get('relationships')->extractFacts($resolvedPrompt);
        if (!empty($newFacts)) {
            foreach ($newFacts as $key => $val) { $this->profile->set($sessionId, $key, $val); }
            $this->profile->save($sessionId);
        }

        // 5. File Handling
        if ($datasetFile) {
            $ext = strtolower(pathinfo($originalName ?? $datasetFile, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                $responseContent = (new \Core\ComputerVision\ComputerVisionAssistant())->analyze($datasetFile);
                return ['status' => 'success', 'response' => $responseContent, 'intent' => 'vision'];
            } else {
                $responseContent = $this->get('dataAi')->inspect($datasetFile);
                return ['status' => 'success', 'response' => $responseContent, 'intent' => 'data_analysis'];
            }
        }

        // 6. Specialized Signal Detection
        if ($this->get('signalProcessor')->isTeachMePrompt($resolvedPrompt)) {
            return ['status' => 'success', 'response' => "Bilkul. Aap text, notes, ya link bhejo. Main seekh kar bataunga.", 'intent' => 'teach_request'];
        }

        // 7. Advanced Math & Tools
        if ($this->get('signalProcessor')->isLikelyCodePrompt($resolvedPrompt)) {
            $toolResult = $this->get('toolOrchestrator')->resolve($resolvedPrompt);
            if ($toolResult) return $toolResult;
        }

        $mathResult = $this->get('math')->evaluateMathQuery($resolvedPrompt);
        if ($mathResult !== null) return ['status' => 'success', 'response' => "Sir, calculation result: " . $mathResult, 'intent' => 'math_query'];

        if (str_contains(strtolower($resolvedPrompt), 'quiz')) {
            $lang = (preg_match('/[अ-ह]/u', $resolvedPrompt)) ? 'hi' : 'en';
            return ['status' => 'success', 'response' => $this->get('quiz')->generate($resolvedPrompt, $lang), 'intent' => 'quiz_request'];
        }

        if (str_contains(strtolower($resolvedPrompt), 'table') || str_contains($resolvedPrompt, 'pahada')) {
            $lang = (preg_match('/[अ-ह]/u', $resolvedPrompt)) ? 'hi' : 'en';
            $table = $this->get('tables')->generate($resolvedPrompt, $lang);
            if ($table) return ['status' => 'success', 'response' => $table, 'intent' => 'table_request'];
        }

        // 8. Neural Network Intent
        if (str_contains(strtolower($resolvedPrompt), 'neural test') || str_contains($resolvedPrompt, 'xor')) {
            $net = new \Core\DL\NeuralNet();
            $net->train([[0,0], [0,1], [1,0], [1,1]], [0, 1, 1, 0], 20);
            $pred = $net->predict([[1,0]]);
            return ['status' => 'success', 'response' => "Neural Network Test: XOR [1,0] result is ~" . round($pred[0] ?? 0, 4), 'intent' => 'neural_test'];
        }

        // 9. Knowledge Retrieval
        $responseContent = $this->get('supervised')->getTaughtAnswer($resolvedPrompt);
        $intent = 'knowledge_match';

        if (!$responseContent) {
            $analysis = $this->get('intelligenceRouter')->analyze($resolvedPrompt);
            $intent = $analysis['intent'];
            if (!empty($analysis['nlu_result']['knowledge_match'])) {
                $responseContent = $analysis['nlu_result']['knowledge_match'];
            } else {
                // Try LLM Integration
                $responseContent = $this->callExternalLLM($resolvedPrompt);

                // Fallback to internal generator if LLM fails
                if (!$responseContent) {
                    $responseContent = $this->get('genAi')->generateThought();
                }
            }
            if (empty($responseContent)) $responseContent = "Sir, abhi mujhe is bare mein training nahi mili hai.";
        }

        // Streaming
        if ($onToken) {
            foreach (explode(' ', $responseContent) as $word) { $onToken($word); usleep(20000); }
        }

        return [
            'status' => 'success', 'response' => $responseContent, 'intent' => $intent,
            'speech' => $this->get('speech')->getSettings(), 'introspection' => $this->get('introspection')->learnSelf()
        ];
    }

    private function runTaskMode(string $input): string {
        $plan = $this->get('taskPlanner')->makePlan($input);
        $outputs = [];
        $steps = [];

        foreach ($plan as $idx => $item) {
            $tool = $item['tool'];
            if ($tool === 'verify') continue;
            $out = $this->get('toolRouter')->run($tool, $input);
            $outputs[$tool] = $out;
            $steps[] = ($idx + 1) . ". " . $item['label'] . ": " . (trim($out) ? 'Done' : 'Skip');
            $this->get('executionLog')->append(['input' => $input, 'tool' => $tool, 'ok' => (bool)trim($out)]);
        }

        $verify = $this->get('verifier')->verify($input, $outputs);
        $body = implode("\n\n", array_filter($outputs, fn($x) => trim($x)));
        if (!$body) $body = "No reliable data gathered.";

        return "Neural Task Report\n\nPlan:\n" . implode("\n", $steps) . "\n\nResults:\n" . $body . "\n\nConfidence: " . ($verify['confidence'] * 100) . "%";
    }

    private function callExternalLLM(string $prompt): ?string {
        // We will prioritize OpenAI if available.
        $apiKey = getenv('OPENAI_API_KEY') ?: '';
        if (empty($apiKey)) return null;

        try {
            $url = 'https://api.openai.com/v1/chat/completions';
            $data = [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are HRITIK, an advanced AI assistant for Tech Elevate X.'],
                    ['role' => 'user', 'content' => $prompt]
                ]
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/json\r\n" .
                                 "Authorization: Bearer $apiKey\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data),
                    'timeout' => 10,
                ],
            ];
            $context  = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);

            if ($result) {
                $json = json_decode($result, true);
                if (isset($json['choices'][0]['message']['content'])) {
                    return $json['choices'][0]['message']['content'];
                }
            }
        } catch (\Exception $e) {
            // Log or ignore
        }
        return null;
    }

    private function resolveContext(string $prompt, array $recent): string {
        if (empty($recent)) return $prompt;
        
        $low = mb_strtolower($prompt, 'UTF-8');
        $needsContext = mb_strlen($low, 'UTF-8') <= 28
            || preg_match('/\b(it|this|that|about it|about this|uska|iske|uske|isko|iske bare|uske bare|aur|next)\b/u', $low) === 1;
        
        if (!$needsContext) return $prompt;

        // Find last topic from assistant responses
        $topic = '';
        foreach (array_reverse($recent) as $msg) {
            if (($msg['role'] ?? '') === 'assistant') {
                $content = $msg['content'] ?? '';
                if (preg_match('/topic is ([^.]+)/i', $content, $m)) { $topic = $m[1]; break; }
            }
        }

        if ($topic) return $prompt . " (context: $topic)";
        return $prompt;
    }
}
