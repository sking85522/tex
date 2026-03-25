<?php
/**
 * Tech Elevate X - Neural Engine (Self-Adapting UI)
 * This engine takes NLP parameters extracted from the Chatbot
 * (interest, budget, urgency) and outputs a "Neural Profile"
 * which dynamically alters the website's HTML/CSS content to match the client's exact needs.
 */

class NeuralEngine {

    private $profile;

    public function __construct() {
        if (!isset($_SESSION['neural_profile'])) {
            $_SESSION['neural_profile'] = [
                'interest' => 'general', // web, app, ai, seo
                'budget' => 'normal',    // low, normal, high
                'urgency' => 'normal',   // normal, urgent
                'industry' => 'business' // ecommerce, healthcare, etc.
            ];
        }
        $this->profile = $_SESSION['neural_profile'];
    }

    /**
     * Updates the user's neural profile based on new NLP data
     */
    public function updateProfile($key, $value) {
        $_SESSION['neural_profile'][$key] = $value;
        $this->profile = $_SESSION['neural_profile'];
    }

    /**
     * Processes the current profile through the "Neural Network" ruleset
     * to determine how the website UI should physically change.
     */
    public function getUIAdaptations() {
        $adaptations = [
            'hero_title' => '',
            'hero_subtitle' => '',
            'highlight_service' => '',
            'discount_badge' => false,
            'urgency_banner' => false
        ];

        // 1. Interest-Based Adaptation (What does the user want?)
        switch ($this->profile['interest']) {
            case 'app':
                $adaptations['hero_title'] = "Build Your Dream Mobile App Fast 📱";
                $adaptations['hero_subtitle'] = "Expert iOS & Android development tailored for your " . $this->profile['industry'] . ".";
                $adaptations['highlight_service'] = 'App Development';
                break;
            case 'web':
                $adaptations['hero_title'] = "High-Converting Websites & Platforms 💻";
                $adaptations['hero_subtitle'] = "We design lightning-fast, SEO-optimized websites for your " . $this->profile['industry'] . ".";
                $adaptations['highlight_service'] = 'Web Development';
                break;
            case 'ai':
                $adaptations['hero_title'] = "Integrate Powerful AI Solutions 🤖";
                $adaptations['hero_subtitle'] = "Automate your workflow with custom LLMs and Data Science.";
                $adaptations['highlight_service'] = 'Backend & APIs';
                break;
            case 'ecommerce':
                $adaptations['hero_title'] = "Launch Your E-Commerce Empire 🛒";
                $adaptations['hero_subtitle'] = "End-to-end shopping platforms with payment integration.";
                $adaptations['highlight_service'] = 'Web + App (Both)';
                break;
        }

        // 2. Budget-Based Adaptation (Pricing Psychology)
        if ($this->profile['budget'] == 'low') {
            $adaptations['discount_badge'] = "🔥 Special 15% Discount Applied For You!";
        } elseif ($this->profile['budget'] == 'high') {
            $adaptations['discount_badge'] = "💎 Premium Enterprise Support Included";
        }

        // 3. Urgency-Based Adaptation (FOMO / Fast Action)
        if ($this->profile['urgency'] == 'urgent') {
            $adaptations['urgency_banner'] = "🚀 Fast-Track Delivery Available! Start your project today to skip the waitlist.";
        }

        return $adaptations;
    }
}
?>
