<?php

use Sajidifti\LaravelCommandCenter\Http\Middleware\CommandCenterSessionHandler;

if (!function_exists('command_center_session')) {
    /**
     * Get or set management session data
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function command_center_session($key = null, $default = null)
    {
        $request = request();
        $sessionData = $request->attributes->get('command_center_session', []);

        // If no key provided, return all session data
        if ($key === null) {
            return $sessionData;
        }

        // If key is an array, set multiple values
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $sessionData[$k] = $v;
            }
            $request->attributes->set('command_center_session', $sessionData);

            return null;
        }

        // Get specific key
        return $sessionData[$key] ?? $default;
    }
}

if (!function_exists('command_center_session_forget')) {
    /**
     * Remove a key from management session
     *
     * @param string|array $keys
     * @return void
     */
    function command_center_session_forget($keys)
    {
        $request = request();
        $sessionData = $request->attributes->get('command_center_session', []);

        foreach ((array) $keys as $key) {
            unset($sessionData[$key]);
        }

        $request->attributes->set('command_center_session', $sessionData);
    }
}

if (!function_exists('command_center_auth_user')) {
    /**
     * Get the authenticated management user's username
     *
     * @return string|null
     */
    function command_center_auth_user()
    {
        return command_center_session('username');
    }
}

if (!function_exists('command_center_session_destroy')) {
    /**
     * Destroy the current management session and its file
     *
     * @return void
     */
    function command_center_session_destroy()
    {
        $request = request();
        $sessionId = $request->attributes->get('command_center_session_id');

        if ($sessionId) {
            $handler = new CommandCenterSessionHandler();
            $handler->destroySession($sessionId);
        }

        $request->attributes->set('command_center_session', []);
    }
}
