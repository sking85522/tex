<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function respond(Request $request)
    {
        $message = strtolower($request->input('message', ''));

        $response = "I'm sorry, I don't understand that. You can ask about our services, pricing, or contact us.";

        if (str_contains($message, 'hello') || str_contains($message, 'hi') || str_contains($message, 'namaste')) {
            $response = "Hello! Namaste! How can I help you today?";
        } elseif (str_contains($message, 'price') || str_contains($message, 'cost') || str_contains($message, 'pricing')) {
            $response = "Our pricing varies based on the service. Please check out our Pricing page for more details, or contact us for a custom quote.";
        } elseif (str_contains($message, 'service') || str_contains($message, 'what do you do')) {
            $response = "We offer Web Development, Mobile Apps, Cloud Architecture, and premium UI/UX Design services.";
        } elseif (str_contains($message, 'contact') || str_contains($message, 'support')) {
            $response = "You can reach us through the Contact page or email us directly at support@example.com.";
        }

        return response()->json(['reply' => $response]);
    }
}
