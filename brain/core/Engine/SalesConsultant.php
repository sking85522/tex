<?php
namespace Core\Engine;

class SalesConsultant {

    public function handleSalesInquiry(string $prompt): string {
        $low = strtolower($prompt);

        $response = "Welcome to Tech Elevate X! I am HRITIK, the lead AI tech consultant. ";

        // E-Commerce
        if (str_contains($low, 'ecommerce') || str_contains($low, 'e-commerce') || str_contains($low, 'shopping')) {
            $response .= "It sounds like you want to build an E-commerce platform. For a robust and scalable solution, I recommend a MERN stack (MongoDB, Express, React, Node.js) or a customized PHP/Laravel backend. ";
            $response .= "\n\nEstimated Timeline: 4 to 8 weeks.\nEstimated Pricing: Starts from ₹40,000 / $500 depending on features (payment gateways, admin panel, etc.).";
        }
        // Mobile App
        elseif (str_contains($low, 'app') || str_contains($low, 'mobile')) {
            $response .= "Developing a mobile app is a great move. We specialize in cross-platform development using React Native or Flutter, which means you get an Android and iOS app from a single codebase. ";
            $response .= "\n\nEstimated Timeline: 6 to 12 weeks.\nEstimated Pricing: Starts from ₹60,000 / $750.";
        }
        // Landing Page / Portfolio
        elseif (str_contains($low, 'landing') || str_contains($low, 'portfolio') || str_contains($low, 'basic website')) {
            $response .= "A professional landing page or portfolio website is crucial for digital presence. We can build a lightning-fast, SEO-optimized site using React, Vue, or modern HTML/CSS/JS. ";
            $response .= "\n\nEstimated Timeline: 1 to 2 weeks.\nEstimated Pricing: Starts from ₹15,000 / $200.";
        }
        // Custom Software / Backend
        elseif (str_contains($low, 'software') || str_contains($low, 'backend') || str_contains($low, 'dashboard')) {
            $response .= "For complex software and custom dashboards, we leverage powerful backends using PHP, Node.js, or Python, coupled with a React or Vue frontend for a smooth UI. ";
            $response .= "\n\nLet's discuss your specific requirements to give you an exact quote. Typically, these start around ₹80,000 / $1000.";
        }
        // Generic / Unknown
        else {
            $response .= "Could you please elaborate on what kind of project you have in mind? (e.g., E-commerce site, Mobile App, Landing Page, or Custom Software). We deliver cutting-edge tech solutions.";
        }

        $response .= "\n\nAre you ready to schedule a deep-dive meeting or finalize the deal? Let me know your email or phone number to proceed.";

        return $response;
    }
}
