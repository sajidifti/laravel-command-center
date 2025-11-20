<?php

namespace Sajidifti\LaravelCommandCenter;

use Illuminate\Support\ServiceProvider;
use Sajidifti\LaravelCommandCenter\Http\Middleware\CommandCenterAuth;
use Sajidifti\LaravelCommandCenter\Http\Middleware\CommandCenterSessionHandler;
use Sajidifti\LaravelCommandCenter\Http\Middleware\RedirectIfCommandCenterAuthenticated;
use Sajidifti\LaravelCommandCenter\Http\Middleware\ShareCommandCenterErrors;

class LaravelCommandCenterServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Load helpers
        $helpersPath = __DIR__ . '/helpers.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        // Merge configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/command-center.php', 'command-center');
    }

    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/command-center.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-command-center');

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/command-center.php' => config_path('command-center.php'),
        ], 'command-center-config');

        // Publish only the stable built assets (app.css + app.js)
        $publicVendorPath = public_path('vendor/laravel-command-center');
        $this->publishes([
            __DIR__ . '/../public/css/app.css' => $publicVendorPath . '/css/app.css',
            __DIR__ . '/../public/js/app.js' => $publicVendorPath . '/js/app.js',
        ], 'command-center-assets');

        // Register commands
        $this->commands([
            Console\InstallCommand::class,
            Console\CleanSessionsCommand::class,
            Console\PublishAssetsCommand::class,
        ]);

        // Register middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('command-center.session', CommandCenterSessionHandler::class);
        $router->aliasMiddleware('command-center.auth', CommandCenterAuth::class);
        $router->aliasMiddleware('command-center.guest', RedirectIfCommandCenterAuthenticated::class);
        $router->aliasMiddleware('command-center.errors', ShareCommandCenterErrors::class);
    }
}
