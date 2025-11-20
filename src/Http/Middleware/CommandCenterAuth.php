<?php

namespace Sajidifti\LaravelCommandCenter\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class CommandCenterAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get management session data
        $sessionData = $request->attributes->get('command_center_session', []);

        // Check if user is authenticated
        if (!isset($sessionData['authenticated']) || $sessionData['authenticated'] !== true) {
            return redirect()->route('command-center.login');
        }

        return $next($request);
    }
}
