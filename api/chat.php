<?php
header('Content-Type: application/json');

// Extremely lightweight, dependency-free PHP Chatbot with basic NLP/Pattern matching
// Supports both English and Hindi

$input = json_decode(file_get_contents('php://input'), true);
$message = strtolower(trim($input['message'] ?? ''));

if (empty($message)) {
    echo json_encode(['reply' => 'Please type a message. / कृपया एक संदेश टाइप करें।']);
    exit;
}

$responses = [
    // Greetings
    '/\b(hi|hello|hey|namaste|pranam)\b/i' => "Namaste! Hello! How can I help you today? / नमस्ते! मैं आपकी कैसे मदद कर सकता हूँ?",

    // Services / Web / App
    '/\b(website|web|app|application|software|development|bna do|banana|create)\b/i' => "We offer comprehensive Web, App, and Software development services. Our static sites start at just ₹299! Would you like to check our pricing page? / हम वेब, ऐप और सॉफ्टवेयर विकास सेवाएं प्रदान करते हैं। हमारी वेबसाइट ₹299 से शुरू होती है।",

    // Pricing
    '/\b(price|pricing|cost|kitna|paise|rupee|rate|charge)\b/i' => "Our pricing is transparent and affordable. Static websites start from ₹299, Web Apps from ₹4999, and Mobile Apps from ₹7999. Check the Pricing page! / हमारी कीमतें बहुत कम हैं, ₹299 से शुरू। कृपया मूल्य निर्धारण (Pricing) पृष्ठ देखें।",

    // Contact / Contact Info
    '/\b(contact|call|phone|email|number|baat|sampark)\b/i' => "You can reach us via the Contact Us page, or email us at info@techelevatex.com. / आप हमसे संपर्क पृष्ठ या info@techelevatex.com के माध्यम से जुड़ सकते हैं।",

    // Careers / Jobs
    '/\b(job|career|hiring|vacancy|kaam|naukri)\b/i' => "We are hiring for multiple roles including Frontend, Backend, Mobile, DevOps, and more! Please check our Careers page. / हम कई पदों के लिए भर्ती कर रहे हैं, कृपया हमारा करियर (Careers) पृष्ठ देखें।",

    // Default Fallback
    'default' => "I'm still learning! Please visit our Services or Pricing pages for more info, or contact our support team. / मुझे अभी भी सीखना है! कृपया अधिक जानकारी के लिए हमारी सेवाएँ या मूल्य निर्धारण पृष्ठ देखें।"
];

$reply = $responses['default'];

foreach ($responses as $pattern => $response) {
    if ($pattern === 'default') continue;

    if (preg_match($pattern, $message)) {
        $reply = $response;
        break;
    }
}

// Add a slight artificial delay for realism
usleep(500000);

echo json_encode(['reply' => $reply]);
