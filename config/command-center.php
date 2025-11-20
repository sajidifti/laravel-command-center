<?php

return [
    /*
     * |--------------------------------------------------------------------------
     * | Management Panel Route Prefix
     * |--------------------------------------------------------------------------
     * |
     * | The URL prefix for accessing the management panel. Change this to
     * | something unique and hard to guess for security purposes.
     * |
     */
    'route_prefix' => env('LARAVEL_COMMAND_CENTER_ROUTE_PREFIX', 'command-center/secret'),

    /*
     * |--------------------------------------------------------------------------
     * | Authentication Credentials
     * |--------------------------------------------------------------------------
     * |
     * | Credentials for accessing the management panel. These are stored in
     * | the .env file and are independent of database authentication.
     * |
     */
    'username' => env('LARAVEL_COMMAND_CENTER_USERNAME', 'admin'),
    'password' => env('LARAVEL_COMMAND_CENTER_PASSWORD', 'password'),

    /*
     * |--------------------------------------------------------------------------
     * | Session Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Configuration for the file-based session system used by the management
     * | panel. This is completely independent of the main application's session.
     * |
     */
    'session' => [
        'lifetime' => env('LARAVEL_COMMAND_CENTER_SESSION_LIFETIME', 120),  // minutes
        'path' => storage_path('framework/management_sessions'),
        'cookie' => 'laravel_command_center_session_id',
        'gc_probability' => 2,  // 2% chance of garbage collection on each request
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Allowed Artisan Commands
     * |--------------------------------------------------------------------------
     * |
     * | List of artisan commands that can be executed through the management
     * | panel for security purposes.
     * |
     */
    'allowed_commands' => [
        'optimize',
        'optimize:clear',
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear',
        'config:cache',
        'route:cache',
        'view:cache',
        'migrate',
        'migrate:fresh',
        'migrate:fresh --seed',
        'migrate:rollback',
        'migrate:status',
        'db:seed',
        'storage:link',
        'queue:restart',
        'command-center:clean-sessions',
    ],
];
