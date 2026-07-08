<?php

class RateLimiter
{
    private $storageDir;
    private $maxAttempts;
    private $windowSeconds;

    public function __construct($storageDir, $maxAttempts = 5, $windowSeconds = 900)
    {
        $this->storageDir = rtrim($storageDir, '/\\');
        $this->maxAttempts = (int) $maxAttempts;
        $this->windowSeconds = (int) $windowSeconds;

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0700, true);
        }
    }

    private function pathFor($ip)
    {
        return $this->storageDir . DIRECTORY_SEPARATOR . 'rl_' . hash('sha256', $ip) . '.json';
    }

    private function read($ip)
    {
        $path = $this->pathFor($ip);
        if (!is_file($path)) {
            return ['failures' => 0, 'blocked_until' => 0];
        }

        $raw = file_get_contents($path);
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return ['failures' => 0, 'blocked_until' => 0];
        }

        return [
            'failures' => (int) ($data['failures'] ?? 0),
            'blocked_until' => (int) ($data['blocked_until'] ?? 0),
        ];
    }

    private function write($ip, array $data)
    {
        file_put_contents($this->pathFor($ip), json_encode($data), LOCK_EX);
    }

    public function isBlocked($ip)
    {
        $data = $this->read($ip);
        return $data['blocked_until'] > time();
    }

    public function remainingBlockSeconds($ip)
    {
        $data = $this->read($ip);
        $remaining = $data['blocked_until'] - time();
        return $remaining > 0 ? $remaining : 0;
    }

    public function registerFailure($ip)
    {
        $data = $this->read($ip);

        if ($data['blocked_until'] > time()) {
            return;
        }

        $data['failures']++;
        if ($data['failures'] >= $this->maxAttempts) {
            $data['blocked_until'] = time() + $this->windowSeconds;
            $data['failures'] = 0;
        }

        $this->write($ip, $data);
    }

    public function reset($ip)
    {
        $path = $this->pathFor($ip);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
