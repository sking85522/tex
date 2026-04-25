<?php
namespace Core\Engine;

/**
 * HRITIK AI - STATE MANAGER
 * Handles User Profile, Short Term Memory, and Conversation State.
 */
class StateManager {
    private $profile;
    private $stm;
    private $memory;
    private $context;

    public function __construct($profile, $stm, $memory, $context) {
        $this->profile = $profile;
        $this->stm = $stm;
        $this->memory = $memory;
        $this->context = $context;
    }

    public function initializeSession(string $sessionId, string $prompt) {
        $this->profile->load($sessionId);
        $this->context->update($prompt);
        $this->stm->add($sessionId, 'user', $prompt);
    }

    public function recordInteraction(string $sessionId, string $prompt, string $mood, bool $hasFile) {
        $this->memory->append($sessionId, [
            'role' => 'user',
            'content' => $prompt . ($hasFile ? ' [Dataset Attached]' : ''),
            'mood' => $mood,
            'timestamp' => time()
        ]);
    }

    public function savePersonalFacts(string $sessionId, array $facts) {
        foreach ($facts as $key => $val) {
            $this->profile->set($sessionId, $key, $val);
        }
        $this->profile->save($sessionId);
    }
}
