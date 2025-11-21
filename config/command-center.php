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
     * | Command Sections
     * |--------------------------------------------------------------------------
     * |
     * | Structured command sections for the management panel UI. Each section
     * | contains metadata and a list of commands with their details.
     * |
     */
    'command_sections' => [
        [
            'name' => 'Optimization Commands',
            'icon' => 'heroicon-o-bolt',
            'color' => 'blue',
            'commands' => [
                [
                    'title' => 'Optimize Application',
                    'description' => 'optimize',
                    'command' => 'optimize',
                    'color' => 'blue',
                ],
                [
                    'title' => 'Clear Optimizations',
                    'description' => 'optimize:clear',
                    'command' => 'optimize:clear',
                    'color' => 'blue',
                ],
                [
                    'title' => 'Cache Config',
                    'description' => 'config:cache',
                    'command' => 'config:cache',
                    'color' => 'green',
                ],
                [
                    'title' => 'Cache Routes',
                    'description' => 'route:cache',
                    'command' => 'route:cache',
                    'color' => 'green',
                ],
                [
                    'title' => 'Cache Views',
                    'description' => 'view:cache',
                    'command' => 'view:cache',
                    'color' => 'green',
                ],
            ],
        ],
        [
            'name' => 'Clear Cache Commands',
            'icon' => 'heroicon-o-trash',
            'color' => 'red',
            'commands' => [
                [
                    'title' => 'Clear Application Cache',
                    'description' => 'cache:clear',
                    'command' => 'cache:clear',
                    'color' => 'red',
                ],
                [
                    'title' => 'Clear Config Cache',
                    'description' => 'config:clear',
                    'command' => 'config:clear',
                    'color' => 'red',
                ],
                [
                    'title' => 'Clear Route Cache',
                    'description' => 'route:clear',
                    'command' => 'route:clear',
                    'color' => 'red',
                ],
                [
                    'title' => 'Clear Compiled Views',
                    'description' => 'view:clear',
                    'command' => 'view:clear',
                    'color' => 'red',
                ],
            ],
        ],
        [
            'name' => 'Database Commands',
            'icon' => 'heroicon-o-circle-stack',
            'color' => 'purple',
            'commands' => [
                [
                    'title' => 'Run Migrations',
                    'description' => 'migrate',
                    'command' => 'migrate',
                    'color' => 'purple',
                ],
                [
                    'title' => 'Migration Status',
                    'description' => 'migrate:status',
                    'command' => 'migrate:status',
                    'color' => 'purple',
                ],
                [
                    'title' => 'Fresh Migration ⚠️',
                    'description' => 'migrate:fresh',
                    'command' => 'migrate:fresh',
                    'color' => 'yellow',
                    'confirmation' => 'This will drop all tables and re-run migrations. Continue?',
                ],
                [
                    'title' => 'Fresh Migration + Seed ⚠️',
                    'description' => 'migrate:fresh --seed',
                    'command' => 'migrate:fresh --seed',
                    'color' => 'yellow',
                    'confirmation' => 'This will drop all tables, re-run migrations, and seed the database. Continue?',
                ],
                [
                    'title' => 'Rollback Migration',
                    'description' => 'migrate:rollback',
                    'command' => 'migrate:rollback',
                    'color' => 'orange',
                    'confirmation' => 'Rollback the last batch of migrations?',
                ],
                [
                    'title' => 'Run Database Seeder',
                    'description' => 'db:seed',
                    'command' => 'db:seed',
                    'color' => 'purple',
                ],
            ],
        ],
        [
            'name' => 'Other Commands',
            'icon' => 'heroicon-o-cog-6-tooth',
            'color' => 'indigo',
            'commands' => [
                [
                    'title' => 'Create Storage Link',
                    'description' => 'storage:link',
                    'command' => 'storage:link',
                    'color' => 'indigo',
                ],
                [
                    'title' => 'Remove Storage Link',
                    'description' => 'storage:unlink',
                    'command' => 'storage:unlink',
                    'color' => 'indigo',
                ],
                [
                    'title' => 'Restart Queue Workers',
                    'description' => 'queue:restart',
                    'command' => 'queue:restart',
                    'color' => 'indigo',
                ],
            ],
        ],
        [
            'name' => 'Command Center Commands',
            'icon' => 'heroicon-o-command-line',
            'color' => 'red',
            'commands' => [
                [
                    'title' => 'Clean Expired Sessions',
                    'description' => 'command-center:clean-sessions',
                    'command' => 'command-center:clean-sessions',
                    'color' => 'red',
                ],
            ],
        ],
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Allowed Artisan Commands
     * |--------------------------------------------------------------------------
     * |
     * | List of artisan commands that can be executed through the management
     * | panel for security purposes. This is used for validation.
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
        'storage:unlink',
        'queue:restart',
        'command-center:clean-sessions',
    ],
];
