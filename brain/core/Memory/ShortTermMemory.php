<?php
namespace Core\Memory;

class ShortTermMemory {
    private array $sessions = [];
    private int $limit;

    public function __construct(int $limit = 10) {
        $this->limit = $limit;
    }

    /**
     * Store a new interaction in the sliding window for a specific session.
     */
    public function add(string $sessionId, string $role, string $content): void {
        if (!isset($this->sessions[$sessionId])) {
            $this->sessions[$sessionId] = [];
        }

        $this->sessions[$sessionId][] = [
            'role' => $role,
            'content' => $content,
            'time' => time()
        ];

        if (count($this->sessions[$sessionId]) > $this->limit) {
            array_shift($this->sessions[$sessionId]);
        }
    }

    /**
     * Retrieve the buffer for a specific session.
     */
    public function get(string $sessionId): array {
        return $this->sessions[$sessionId] ?? [];
    }

    /**
     * Returns a flat summary of the session context.
     */
    public function getSummary(string $sessionId): string {
        $buffer = $this->get($sessionId);
        $summary = "";
        foreach ($buffer as $msg) {
            $summary .= $msg['role'] . ": " . $msg['content'] . "\n";
        }
        return $summary;
    }

    public function clear(string $sessionId): void {
        unset($this->sessions[$sessionId]);
    }
}
