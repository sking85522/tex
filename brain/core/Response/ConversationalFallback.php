<?php
namespace Core\Response;

class ConversationalFallback {
    private array $fallbacks = [
        "Hmm, mujhe iska answer abhi nahi pata, par main seekh raha hoon!",
        "Main abhi is topic par research kar raha hoon, kya hum kisi aur cheez par baat karein?",
        "Interesting point! Par filhaal main sirf Math, Data aur Logic mein expert hoon.",
        "Aapki baat mere database mein nahi mili. Kya aap isey simplify kar sakte hain?",
        "Hritik AI Engine abhi environment scan kar raha hai, please try another query."
    ];

    /**
     * Returns a random polite fallback message.
     */
    public function getFallback(): string {
        return $this->fallbacks[array_rand($this->fallbacks)];
    }
}
