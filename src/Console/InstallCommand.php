<?php

namespace Sajidifti\LaravelCommandCenter\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'command-center:install {--with-assets : Publish assets during install (optional)}';

    protected $description = 'Install the Management Panel package';

    public function handle()
    {
        $this->info('Installing Laravel Management Panel...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'command-center-config',
            '--force' => true,
        ]);

        // Optionally publish assets only when user requests it
        if ($this->option('with-assets')) {
            $this->info('Publishing management panel assets...');
            $this->call('vendor:publish', [
                '--tag' => 'command-center-assets',
                '--force' => true,
            ]);
        } else {
            $this->line('Skipping asset publish. Run `php artisan vendor:publish --tag=command-center-assets` to publish compiled assets.');
        }

        // Create session directory
        $sessionPath = storage_path('framework/management_sessions');
        if (!File::exists($sessionPath)) {
            File::makeDirectory($sessionPath, 0755, true);
            File::put($sessionPath . '/.gitignore', "*\n!.gitignore\n");
            $this->info('Created management sessions directory');
        }

        // Check if .env has management settings
        $envFile = base_path('.env');
        $envContent = File::get($envFile);
        $updated = false;

        $requiredKeys = [
            'LARAVEL_COMMAND_CENTER_ROUTE_PREFIX' => 'command-center/secret',
            'LARAVEL_COMMAND_CENTER_USERNAME' => 'admin',
            'LARAVEL_COMMAND_CENTER_PASSWORD' => 'password',
            'LARAVEL_COMMAND_CENTER_SESSION_LIFETIME' => '120',
        ];

        if (!str_contains($envContent, '# Laravel Command Center Configuration')) {
            File::append($envFile, "\n# Laravel Command Center Configuration\n");
        }

        foreach ($requiredKeys as $key => $defaultValue) {
            if (!str_contains($envContent, $key . '=')) {
                File::append($envFile, "{$key}={$defaultValue}\n");
                $this->info("Added {$key} to .env");
                $updated = true;
            }
        }

        if ($updated) {
            $this->info('Updated .env with missing configuration keys.');
        } else {
            $this->line('No changes made to .env (configuration already exists).');
        }

        $this->newLine();
        $this->info('✓ Laravel Command Center installed successfully!');
        $this->newLine();

        $this->warn('⚠ IMPORTANT SECURITY STEPS:');
        $this->line('1. Change LARAVEL_COMMAND_CENTER_ROUTE_PREFIX in .env to something unique');
        $this->line('2. Update LARAVEL_COMMAND_CENTER_USERNAME and LARAVEL_COMMAND_CENTER_PASSWORD');
        $this->line('3. Clear your config cache: php artisan config:clear');
        $this->newLine();

        $prefix = config('command-center.route_prefix', 'command-center/secret');
        $this->info('Access your laravel command center at: ' . url($prefix));

        return self::SUCCESS;
    }
}
