<?php

namespace Sajidifti\LaravelCommandCenter\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ShareCommandCenterErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get command center session data
        $sessionData = $request->attributes->get('command_center_session', []);

        // Reconstruct errors ViewErrorBag from session data
        $errors = new ViewErrorBag();
        if (isset($sessionData['errors']) && is_array($sessionData['errors'])) {
            // Session data is JSON decoded, so we need to reconstruct the ViewErrorBag
            foreach ($sessionData['errors'] as $key => $messages) {
                if (is_array($messages)) {
                    $errors->put($key, new \Illuminate\Support\MessageBag($messages));
                }
            }
        }
        view()->share('errors', $errors);

        // Share old input data for form repopulation
        $oldInput = $sessionData['_old_input'] ?? [];
        view()->share('old', $oldInput);

        return $next($request);
    }
}
