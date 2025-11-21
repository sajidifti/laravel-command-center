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
     * | System Information Items
     * |--------------------------------------------------------------------------
     * |
     * | Define which system information items to display and how to retrieve them.
     * | Each item has a label, icon, and value source (callable or config path).
     * |
     */
    'system_info_items' => [
        [
            'key' => 'php_version',
            'label' => 'PHP Version',
            'icon' => 'heroicon-o-code-bracket',
            'value' => fn() => PHP_VERSION,
        ],
        [
            'key' => 'laravel_version',
            'label' => 'Laravel Version',
            'icon' => 'heroicon-o-cube',
            'value' => fn() => app()->version(),
        ],
        [
            'key' => 'environment',
            'label' => 'Environment',
            'icon' => 'heroicon-o-server',
            'value' => fn() => app()->environment(),
        ],
        [
            'key' => 'debug_mode',
            'label' => 'Debug Mode',
            'icon' => 'heroicon-o-bug-ant',
            'value' => fn() => config('app.debug') ? 'Enabled' : 'Disabled',
        ],
        [
            'key' => 'timezone',
            'label' => 'Timezone',
            'icon' => 'heroicon-o-clock',
            'value' => fn() => config('app.timezone'),
        ],
        [
            'key' => 'database_connection',
            'label' => 'Database',
            'icon' => 'heroicon-o-circle-stack',
            'value' => fn() => config('database.default'),
        ],
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Environment Settings Categories
     * |--------------------------------------------------------------------------
     * |
     * | Structured environment settings categories for the management panel.
     * | Each category contains metadata and a list of environment variables.
     * |
     */
    'env_settings_categories' => [
        [
            'key' => 'app',
            'name' => 'Application Settings',
            'icon' => 'heroicon-o-cog-6-tooth',
            'fields' => [
                [
                    'key' => 'APP_NAME',
                    'label' => 'Application Name',
                    'type' => 'text'
                ],
                [
                    'key' => 'APP_ENV',
                    'label' => 'Environment',
                    'type' => 'text'
                ],
                [
                    'key' => 'APP_DEBUG',
                    'label' => 'Debug Mode',
                    'type' => 'text'
                ],
                [
                    'key' => 'APP_TIMEZONE',
                    'label' => 'Timezone',
                    'type' => 'text',
                    'default' => 'UTC'
                ],
                [
                    'key' => 'APP_URL',
                    'label' => 'Application URL',
                    'type' => 'text'
                ],
            ],
        ],
        [
            'key' => 'database',
            'name' => 'Database Settings',
            'icon' => 'heroicon-o-circle-stack',
            'fields' => [
                [
                    'key' => 'DB_CONNECTION',
                    'label' => 'Connection',
                    'type' => 'text'
                ],
                [
                    'key' => 'DB_HOST',
                    'label' => 'Host',
                    'type' => 'text'
                ],
                [
                    'key' => 'DB_PORT',
                    'label' => 'Port',
                    'type' => 'text'
                ],
                [
                    'key' => 'DB_DATABASE',
                    'label' => 'Database',
                    'type' => 'text'
                ],
                [
                    'key' => 'DB_USERNAME',
                    'label' => 'Username',
                    'type' => 'text'
                ],
                [
                    'key' => 'DB_PASSWORD',
                    'label' => 'Password',
                    'type' => 'password'
                ],
            ],
        ],
        [
            'key' => 'mail',
            'name' => 'Mail Settings',
            'icon' => 'heroicon-o-envelope',
            'fields' => [
                [
                    'key' => 'MAIL_MAILER',
                    'label' => 'Mailer',
                    'type' => 'text'
                ],
                [
                    'key' => 'MAIL_HOST',
                    'label' => 'Host',
                    'type' => 'text'
                ],
                [
                    'key' => 'MAIL_PORT',
                    'label' => 'Port',
                    'type' => 'text'
                ],
                [
                    'key' => 'MAIL_USERNAME',
                    'label' => 'Username',
                    'type' => 'text'
                ],
                [
                    'key' => 'MAIL_PASSWORD',
                    'label' => 'Password',
                    'type' => 'password'
                ],
                [
                    'key' => 'MAIL_ENCRYPTION',
                    'label' => 'Encryption',
                    'type' => 'text'
                ],
                [
                    'key' => 'MAIL_FROM_ADDRESS',
                    'label' => 'From Address',
                    'type' => 'text'
                ],
                [
                    'key' => 'MAIL_FROM_NAME',
                    'label' => 'From Name',
                    'type' => 'text'
                ],
            ],
        ],
        [
            'key' => 'cache',
            'name' => 'Cache Settings',
            'icon' => 'heroicon-o-bolt',
            'fields' => [
                [
                    'key' => 'CACHE_STORE',
                    'label' => 'Cache Store',
                    'type' => 'text'
                ],
                [
                    'key' => 'CACHE_PREFIX',
                    'label' => 'Cache Prefix',
                    'type' => 'text'
                ],
            ],
        ],
        [
            'key' => 'queue',
            'name' => 'Queue Settings',
            'icon' => 'heroicon-o-queue-list',
            'fields' => [
                [
                    'key' => 'QUEUE_CONNECTION',
                    'label' => 'Queue Connection',
                    'type' => 'text'
                ],
            ],
        ],
        [
            'key' => 'session',
            'name' => 'Session Settings',
            'icon' => 'heroicon-o-key',
            'fields' => [
                [
                    'key' => 'SESSION_DRIVER',
                    'label' => 'Session Driver',
                    'type' => 'text'
                ],
                [
                    'key' => 'SESSION_LIFETIME',
                    'label' => 'Session Lifetime',
                    'type' => 'text'
                ],
            ],
        ],
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
