<?php
namespace Core\MachineLearningAlgorithms;

if (file_exists(dirname(__DIR__, 2) . '/modules/nlphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/nlphp/autoload.php';
}

use NLPHP\Classification\NaiveBayes;

class SimpleTextClassifier {
    private NaiveBayes $nb;
    private array $trainingTexts = [];
    private array $trainingLabels = [];

    public function __construct() {
        $this->nb = new NaiveBayes();
        $this->trainDefaultIntents();
    }

    private function trainDefaultIntents() {
        // Greetings
        $this->train("hello hi hey greetings namaste", "greeting");
        $this->train("kaise ho hritik kya haal hai sab thik", "greeting");
        $this->train("kya chal raha hai kya kar rahe ho", "greeting");
        $this->train("kaam shuru karo ready ho", "greeting");
        
        // Identity
        $this->train("who are you what is your name", "identity");
        $this->train("tum kaun ho tera naam kya hai", "identity");
        $this->train("apne baare mein batao hritik", "identity");
        
        // Creator
        $this->train("who created you who made you architect mastermind", "creator");
        $this->train("tujhe kisne banaya tera baap kaun hai", "creator");
        $this->train("sachin ne banaya kya tujhe", "creator");

        // Math & Logic
        $this->train("add subtract multiply divide matrix math numphp calculate", "math");
        $this->train("hisaab karo maths solve karo calculation", "math");
        $this->train("matrix multiplication dikhao", "math");
        
        // Machine Learning
        $this->train("run rl reinforcement learning agent grid maze train bellman", "rl");
        $this->train("test neural network deep learning brain train neural net sequential", "neural_test");
        $this->train("ai kaise kaam karta hai training shuru karo", "neural_test");
        
        // Data Analysis
        $this->train("analyze data statistics report mean median mode standard deviation", "data_analysis");
        $this->train("data ka analysis karo report banao stats dikhao", "data_analysis");
        
        // Creative
        $this->train("generate creative thought kuch naya bolo story sunao", "creative");
        $this->train("kuch shayeri ya thought sunao", "creative");
        $this->train("bore ho raha hoon kuch majedar bolo", "creative");

        // Farewell
        $this->train("bye goodbye cya see you", "farewell");
        $this->train("chalo fir milenge band karo", "farewell");
        $this->train("theek hai abhi aur kaam nahi hai", "farewell");
        
        // Re-fit the model after deep training
        $this->fit();
    }

    /**
     * Buffer a document and a label for training.
     */
    public function train(string $document, string $label) {
        $this->trainingTexts[] = $document;
        $this->trainingLabels[] = $label;
    }

    /**
     * Finalize training by fitting the model.
     */
    public function fit() {
        if (!empty($this->trainingTexts)) {
            $this->nb->fit($this->trainingTexts, $this->trainingLabels);
        }
    }

    /**
     * Predict the label using Naive Bayes.
     */
    public function predict(string $text): string {
        $prediction = $this->nb->predict($text);
        return $prediction ?? 'unknown';
    }
}
