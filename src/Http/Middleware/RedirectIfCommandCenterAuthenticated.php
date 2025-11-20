<?php

namespace Sajidifti\LaravelCommandCenter\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class RedirectIfCommandCenterAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get management session data
        $sessionData = $request->attributes->get('command_center_session', []);

        // Check if user is already authenticated
        if (isset($sessionData['authenticated']) && $sessionData['authenticated'] === true) {
            return redirect()->route('command-center.index');
        }

        return $next($request);
    }
}
