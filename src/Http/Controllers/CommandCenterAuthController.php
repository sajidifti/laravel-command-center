<?php

namespace Sajidifti\LaravelCommandCenter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Sajidifti\LaravelCommandCenter\Http\Middleware\CommandCenterSessionHandler;

class CommandCenterAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('laravel-command-center::login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Get credentials from config
        $configUsername = config('command-center.username');
        $configPassword = config('command-center.password');

        // Verify credentials
        if ($username === $configUsername && $password === $configPassword) {
            // Get current session data
            $sessionData = $request->attributes->get('command_center_session', []);

            // Set authentication data
            $sessionData['authenticated'] = true;
            $sessionData['username'] = $username;
            $sessionData['login_time'] = now()->timestamp;

            // Clear any previous errors
            unset($sessionData['errors'], $sessionData['_old_input']);

            // Update session data
            $request->attributes->set('command_center_session', $sessionData);

            return redirect()->route('command-center.index');
        }

        // Store errors in our custom session (convert ViewErrorBag to array for JSON storage)
        $sessionData = $request->attributes->get('command_center_session', []);
        $errors = new ViewErrorBag();
        $errors->put('default', new MessageBag(['username' => 'Invalid credentials provided.']));
        
        // Convert ViewErrorBag to array for JSON serialization
        $errorsArray = [];
        foreach ($errors->getBags() as $key => $bag) {
            $errorsArray[$key] = $bag->toArray();
        }
        
        $sessionData['errors'] = $errorsArray;
        $sessionData['_old_input'] = $request->only('username');
        $request->attributes->set('command_center_session', $sessionData);

        return redirect()->back();
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Get session ID and destroy the session file
        $sessionId = $request->attributes->get('command_center_session_id');

        if ($sessionId) {
            $handler = new CommandCenterSessionHandler();
            $handler->destroySession($sessionId);
        }

        // Clear session data
        $request->attributes->set('command_center_session', []);

        $cookieName = config('command-center.session.cookie');

        return redirect()
            ->route('command-center.login')
            ->withCookie(cookie()->forget($cookieName))
            ->with('success', 'Successfully logged out!');
    }
}
