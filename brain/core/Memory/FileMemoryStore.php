<?php
namespace Core\Memory;

class FileMemoryStore {
    private string $storagePath;

    public function __construct(string $storageDir = null) {
        if (!$storageDir) {
            $this->storagePath = dirname(__DIR__, 2) . '/storage/data';
        } else {
            $this->storagePath = rtrim($storageDir, '/');
        }

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }
    }

    /**
     * Get path for a specific namespace
     */
    private function getNamespacePath(string $namespace): string {
        $path = $this->storagePath . '/' . $namespace;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    /**
     * Set/Save data in a namespace
     */
    public function set(string $id, array $data, string $namespace = 'chats'): bool {
        $path = $this->getNamespacePath($namespace);
        $file = $path . '/' . md5($id) . '.json';
        return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Get data from a namespace
     */
    public function get(string $id, string $namespace = 'chats'): array {
        $path = $this->getNamespacePath($namespace);
        $file = $path . '/' . md5($id) . '.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            return json_decode($content, true) ?? [];
        }
        return [];
    }

    /**
     * Append to data in a namespace
     */
    public function append(string $id, $data, string $namespace = 'chats'): bool {
        $memory = $this->get($id, $namespace);
        $memory[] = $data;
        return $this->set($id, $memory, $namespace);
    }
}
