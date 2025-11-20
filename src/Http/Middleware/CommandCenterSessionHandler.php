<?php

namespace Sajidifti\LaravelCommandCenter\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Closure;

class CommandCenterSessionHandler
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get config values
        $cookieName = config('command-center.session.cookie');
        $lifetime = config('command-center.session.lifetime');

        // Get or create session ID from cookie
        $sessionId = $request->cookie($cookieName);

        // Check if session ID is encrypted (old format) or invalid
        // Valid session ID should be exactly 40 alphanumeric characters
        if (!$sessionId || !ctype_alnum($sessionId) || strlen($sessionId) !== 40) {
            // Generate new plain session ID
            $sessionId = Str::random(40);
        }

        // Randomly trigger garbage collection to clean expired sessions (2% chance)
        if (mt_rand(1, 100) <= 2) {
            $this->garbageCollection();
        }

        // Load session data from file
        $sessionData = $this->loadSession($sessionId);

        // Store session data in request for access by other middleware/controllers
        $request->attributes->set('command_center_session', $sessionData);
        $request->attributes->set('command_center_session_id', $sessionId);

        $response = $next($request);

        // Save session data back to file
        $updatedSessionData = $request->attributes->get('command_center_session', []);
        $this->saveSession($sessionId, $updatedSessionData);

        // Set cookie with session ID (httpOnly, secure in production)
        $cookie = cookie(
            $cookieName,
            $sessionId,
            $lifetime,
            '/',
            null,
            $request->secure(),
            true,  // httpOnly
            false,
            'Lax'
        );

        // Check if response is a StreamedResponse (doesn't support withCookie)
        if ($response instanceof StreamedResponse) {
            // For streamed responses, set cookie header directly
            $response->headers->setCookie($cookie);

            return $response;
        }

        return $response->withCookie($cookie);
    }

    /**
     * Load session data from file
     */
    private function loadSession(string $sessionId): array
    {
        $sessionFile = $this->getSessionFilePath($sessionId);

        if (!file_exists($sessionFile)) {
            return [];
        }

        $content = file_get_contents($sessionFile);
        $data = json_decode($content, true);

        if (!$data || !is_array($data)) {
            return [];
        }

        // Check if session has expired
        if (isset($data['_expire_at']) && time() > $data['_expire_at']) {
            $this->destroySession($sessionId);

            return [];
        }

        return $data;
    }

    /**
     * Save session data to file
     */
    private function saveSession(string $sessionId, array $data): void
    {
        $lifetime = config('command-center.session.lifetime');

        // Add expiration timestamp
        $data['_expire_at'] = time() + ($lifetime * 60);
        $data['_last_activity'] = time();

        $sessionFile = $this->getSessionFilePath($sessionId);
        $sessionDir = dirname($sessionFile);

        // Ensure directory exists
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755, true);
        }

        file_put_contents($sessionFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Destroy session file
     */
    public function destroySession(string $sessionId): void
    {
        $sessionFile = $this->getSessionFilePath($sessionId);

        if (file_exists($sessionFile)) {
            unlink($sessionFile);
        }
    }

    /**
     * Garbage collection - clean expired session files
     */
    private function garbageCollection(): void
    {
        $sessionDir = config('command-center.session.path');

        if (!is_dir($sessionDir)) {
            return;
        }

        $files = glob($sessionDir . '/*.json');
        $now = time();

        foreach ($files as $file) {
            if (!file_exists($file)) {
                continue;
            }

            $content = file_get_contents($file);
            $data = json_decode($content, true);

            // Delete if expired or invalid
            if (
                !$data ||
                !is_array($data) ||
                (isset($data['_expire_at']) && $now > $data['_expire_at'])
            ) {
                unlink($file);
            }
        }
    }

    /**
     * Clean all expired sessions (useful for scheduled tasks)
     */
    public static function cleanExpiredSessions(): int
    {
        $sessionDir = config('command-center.session.path');
        $cleaned = 0;

        if (!is_dir($sessionDir)) {
            return $cleaned;
        }

        $files = glob($sessionDir . '/*.json');
        $now = time();

        foreach ($files as $file) {
            if (!file_exists($file)) {
                continue;
            }

            $content = file_get_contents($file);
            $data = json_decode($content, true);

            // Delete if expired or invalid
            if (
                !$data ||
                !is_array($data) ||
                (isset($data['_expire_at']) && $now > $data['_expire_at'])
            ) {
                unlink($file);
                $cleaned++;
            }
        }

        return $cleaned;
    }

    /**
     * Get session file path
     */
    private function getSessionFilePath(string $sessionId): string
    {
        $sessionPath = config('command-center.session.path');

        return $sessionPath . '/' . $sessionId . '.json';
    }
}
