<?php

namespace Sajidifti\LaravelCommandCenter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;

class CommandCenterController extends Controller
{
    /**
     * Display the management interface
     */
    public function index()
    {
        return view('laravel-command-center::index');
    }

    /**
     * Execute an artisan command and stream output in real-time
     */
    public function executeCommand(Request $request)
    {
        $request->validate([
            'command' => 'required|string',
        ]);

        $command = $request->input('command');
        $allowedCommands = config('command-center.allowed_commands', [
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
        ]);

        // Check if command is allowed
        if (!in_array($command, $allowedCommands)) {
            return response()->json([
                'success' => false,
                'output' => 'Command not allowed for security reasons.',
            ], 403);
        }

        return response()->stream(function () use ($command) {
            // Parse command and arguments
            $commandParts = explode(' ', $command);
            $commandName = array_shift($commandParts);

            // Build arguments array
            $arguments = [];
            foreach ($commandParts as $part) {
                if (strpos($part, '--') === 0) {
                    $arguments[$part] = true;
                }
            }

            try {
                // Create a custom output handler for streaming
                $output = new class extends Output {
                    protected function doWrite(string $message, bool $newline): void
                    {
                        echo 'data: ' . json_encode(['type' => 'output', 'message' => $message . ($newline ? "\n" : '')]) . "\n\n";
                        if (ob_get_level() > 0) {
                            ob_flush();
                        }
                        flush();
                    }
                };

                // Send start event
                echo 'data: ' . json_encode(['type' => 'start', 'command' => $command]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                // Execute the artisan command
                $exitCode = Artisan::call($commandName, $arguments, $output);

                // Send completion event
                echo 'data: ' . json_encode([
                    'type' => 'complete',
                    'exit_code' => $exitCode,
                    'success' => $exitCode === 0
                ]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            } catch (\Exception $e) {
                echo 'data: ' . json_encode([
                    'type' => 'error',
                    'message' => $e->getMessage()
                ]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Execute storage:unlink command
     */
    public function unlinkStorage(Request $request)
    {
        try {
            $publicPath = public_path('storage');

            if (is_link($publicPath)) {
                unlink($publicPath);
                $output = 'The [public/storage] directory has been unlinked.';
                $success = true;
            } elseif (is_dir($publicPath)) {
                $output = 'The [public/storage] directory exists but is not a symbolic link. Please remove it manually.';
                $success = false;
            } else {
                $output = 'The [public/storage] symbolic link does not exist.';
                $success = false;
            }

            return response()->json([
                'success' => $success,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        try {
            $info = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
                'timezone' => config('app.timezone'),
                'database_connection' => config('database.default'),
            ];

            return response()->json([
                'success' => true,
                'data' => $info,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get environment settings
     */
    public function getEnvSettings()
    {
        try {
            $settings = [
                'app' => [
                    'APP_NAME' => env('APP_NAME'),
                    'APP_ENV' => env('APP_ENV'),
                    'APP_DEBUG' => env('APP_DEBUG', false) ? 'true' : 'false',
                    'APP_TIMEZONE' => env('APP_TIMEZONE', 'UTC'),
                    'APP_URL' => env('APP_URL'),
                ],
                'database' => [
                    'DB_CONNECTION' => env('DB_CONNECTION'),
                    'DB_HOST' => env('DB_HOST'),
                    'DB_PORT' => env('DB_PORT'),
                    'DB_DATABASE' => env('DB_DATABASE'),
                    'DB_USERNAME' => env('DB_USERNAME'),
                ],
                'mail' => [
                    'MAIL_MAILER' => env('MAIL_MAILER'),
                    'MAIL_HOST' => env('MAIL_HOST'),
                    'MAIL_PORT' => env('MAIL_PORT'),
                    'MAIL_USERNAME' => env('MAIL_USERNAME'),
                    'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
                    'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
                    'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
                ],
                'cache' => [
                    'CACHE_STORE' => env('CACHE_STORE'),
                    'CACHE_PREFIX' => env('CACHE_PREFIX'),
                ],
                'queue' => [
                    'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
                ],
                'session' => [
                    'SESSION_DRIVER' => env('SESSION_DRIVER'),
                    'SESSION_LIFETIME' => env('SESSION_LIFETIME'),
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update environment settings
     */
    public function updateEnvSettings(Request $request)
    {
        try {
            $settings = $request->input('settings', []);
            $envPath = base_path('.env');

            if (!file_exists($envPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '.env file not found',
                ], 404);
            }

            $envContent = file_get_contents($envPath);

            foreach ($settings as $key => $value) {
                // Escape special characters and wrap in quotes if needed
                $escapedValue = $this->escapeEnvValue($value);

                // Check if key exists
                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    // Update existing key
                    $envContent = preg_replace(
                        "/^{$key}=.*/m",
                        "{$key}={$escapedValue}",
                        $envContent
                    );
                } else {
                    // Add new key at the end
                    $envContent .= "\n{$key}={$escapedValue}";
                }
            }

            file_put_contents($envPath, $envContent);

            return response()->json([
                'success' => true,
                'message' => 'Environment settings updated successfully. Please run "config:clear" for changes to take effect.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance(Request $request)
    {
        try {
            $action = $request->input('action');  // 'up' or 'down'
            $secret = $request->input('secret');

            if ($action === 'down') {
                $options = [];
                if ($secret) {
                    $options['--secret'] = $secret;
                }

                Artisan::call('down', $options);

                return response()->json([
                    'success' => true,
                    'message' => 'Application is now in maintenance mode.' . ($secret ? " Secret: {$secret}" : ''),
                    'status' => 'down',
                    'secret' => $secret,
                ]);
            } else {
                Artisan::call('up');

                return response()->json([
                    'success' => true,
                    'message' => 'Application is now live.',
                    'status' => 'up',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check maintenance mode status
     */
    public function getMaintenanceStatus()
    {
        try {
            $downFile = storage_path('framework/down');
            $isDown = file_exists($downFile);

            $secret = null;
            if ($isDown) {
                $payload = json_decode(file_get_contents($downFile), true);
                $secret = $payload['secret'] ?? null;
            }

            return response()->json([
                'success' => true,
                'status' => $isDown ? 'down' : 'up',
                'secret' => $secret,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get raw .env file content
     */
    public function getEnvFile()
    {
        try {
            $envPath = base_path('.env');

            if (!file_exists($envPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '.env file not found',
                ], 404);
            }

            $content = file_get_contents($envPath);

            return response()->json([
                'success' => true,
                'content' => $content,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update raw .env file content
     */
    public function updateEnvFile(Request $request)
    {
        try {
            $content = $request->input('content');
            $envPath = base_path('.env');

            // Create backup
            $backupPath = base_path('.env.backup.' . date('Y-m-d_H-i-s'));
            if (file_exists($envPath)) {
                copy($envPath, $backupPath);
            }

            file_put_contents($envPath, $content);

            return response()->json([
                'success' => true,
                'message' => 'Environment file updated successfully. Backup created at: ' . basename($backupPath),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Escape environment value
     */
    private function escapeEnvValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_null($value)) {
            return 'null';
        }

        // If value contains spaces or special characters, wrap in quotes
        if (preg_match('/\s|[#$&()]/', $value)) {
            return '"' . str_replace('"', '\"', $value) . '"';
        }

        return $value;
    }
}
