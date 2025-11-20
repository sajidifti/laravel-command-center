<?php

namespace Sajidifti\LaravelCommandCenter\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'command-center:install 
                            {--with-config : Publish configuration file}
                            {--with-assets : Publish compiled assets}
                            {--with-env : Add environment variables to .env}
                            {--complete : Publish everything with default values (non-interactive)}';

    protected $description = 'Install the Laravel Command Center package (interactive)';

    private bool $isComplete = false;

    public function handle()
    {
        $this->info('Initializing Laravel Command Center installation...');
        $this->newLine();

        $this->isComplete = $this->option('complete');

        // Create session directory first (always needed)
        $this->createSessionDirectory();

        // Determine what to publish based on flags or interactive prompts
        $publishConfig = $this->shouldPublishConfig();
        $publishAssets = $this->shouldPublishAssets();
        $addEnvVars = $this->shouldAddEnvVars();

        // Execute publishing steps
        if ($publishConfig) {
            $this->publishConfiguration();
        } else {
            $this->showConfigInstructions();
        }

        if ($publishAssets) {
            $this->publishAssets();
        } else {
            $this->showAssetsInstructions();
        }

        if ($addEnvVars) {
            $this->addEnvironmentVariables();
        } else {
            $this->showEnvInstructions();
        }

        // Final success message
        $this->newLine();
        $this->info('✓ Laravel Command Center installed successfully!');
        $this->newLine();

        if ($addEnvVars) {
            $this->displaySecurityWarnings();
        }

        return self::SUCCESS;
    }

    /**
     * Create the session directory for the command center
     */
    private function createSessionDirectory(): void
    {
        $sessionPath = storage_path('framework/management_sessions');
        if (!File::exists($sessionPath)) {
            File::makeDirectory($sessionPath, 0755, true);
            File::put($sessionPath . '/.gitignore', "*\n!.gitignore\n");
            $this->info('✓ Created management sessions directory');
        }
    }

    /**
     * Determine if configuration should be published
     */
    private function shouldPublishConfig(): bool
    {
        if ($this->option('with-config')) {
            return true;
        }

        if ($this->isComplete) {
            return true;
        }

        return $this->confirm('Publish configuration file?', true);
    }

    /**
     * Determine if assets should be published
     */
    private function shouldPublishAssets(): bool
    {
        if ($this->option('with-assets')) {
            return true;
        }

        if ($this->isComplete) {
            return true;
        }

        return $this->confirm('Publish compiled assets (CSS/JS)?', true);
    }

    /**
     * Determine if environment variables should be added
     */
    private function shouldAddEnvVars(): bool
    {
        if ($this->option('with-env')) {
            return true;
        }

        if ($this->isComplete) {
            return true;
        }

        return $this->confirm('Add environment variables to .env (and .env.example)?', true);
    }

    /**
     * Publish the configuration file
     */
    private function publishConfiguration(): void
    {
        $this->info('Publishing configuration file...');
        $this->call('vendor:publish', [
            '--tag' => 'command-center-config',
            '--force' => true,
        ]);
        $this->info('✓ Configuration published');
    }

    /**
     * Publish the compiled assets
     */
    private function publishAssets(): void
    {
        $this->info('Publishing compiled assets...');
        $this->call('vendor:publish', [
            '--tag' => 'command-center-assets',
            '--force' => true,
        ]);
        $this->info('✓ Assets published');
    }

    /**
     * Add environment variables to .env and .env.example
     */
    private function addEnvironmentVariables(): void
    {
        $this->info('Configuring environment variables...');
        $this->newLine();

        // Get values from user or use defaults
        $routePrefix = $this->getRoutePrefix();
        $username = $this->getUsername();
        $password = $this->getPassword();
        $sessionLifetime = $this->getSessionLifetime();

        // Update .env file
        $this->updateEnvFile($routePrefix, $username, $password, $sessionLifetime);

        // Update .env.example file
        $this->updateEnvExampleFile();

        $this->newLine();
        $this->info('✓ Environment variables configured');
    }

    /**
     * Get the route prefix from user or generate default
     */
    private function getRoutePrefix(): string
    {
        $defaultSecret = Str::random(16);
        $defaultPrefix = "command-center/{$defaultSecret}";

        if ($this->isComplete) {
            $this->line("Route Prefix: {$defaultPrefix}");
            return $defaultPrefix;
        }

        $routePrefix = $this->ask(
            'Route Prefix (include a secret path for security)',
            $defaultPrefix
        );

        return $routePrefix;
    }

    /**
     * Get the username from user or use default
     */
    private function getUsername(): string
    {
        if ($this->isComplete) {
            $this->line('Username: admin');
            return 'admin';
        }

        return $this->ask('Username', 'admin');
    }

    /**
     * Get the password from user or generate random
     */
    private function getPassword(): string
    {
        $defaultPassword = Str::random(16);

        if ($this->isComplete) {
            $this->line("Password: {$defaultPassword}");
            return $defaultPassword;
        }

        $password = $this->secret("Password (leave empty for random: {$defaultPassword})");

        return empty($password) ? $defaultPassword : $password;
    }

    /**
     * Get the session lifetime from user or use default
     */
    private function getSessionLifetime(): string
    {
        if ($this->isComplete) {
            $this->line('Session Lifetime: 120 minutes');
            return '120';
        }

        return $this->ask('Session Lifetime (minutes)', '120');
    }

    /**
     * Update the .env file with the configuration values
     */
    private function updateEnvFile(string $routePrefix, string $username, string $password, string $sessionLifetime): void
    {
        $envFile = base_path('.env');

        if (!File::exists($envFile)) {
            $this->warn('.env file not found. Skipping .env update.');
            return;
        }

        $envContent = File::get($envFile);

        $envVars = [
            'LARAVEL_COMMAND_CENTER_ROUTE_PREFIX' => $routePrefix,
            'LARAVEL_COMMAND_CENTER_USERNAME' => $username,
            'LARAVEL_COMMAND_CENTER_PASSWORD' => $password,
            'LARAVEL_COMMAND_CENTER_SESSION_LIFETIME' => $sessionLifetime,
        ];

        // Add section header if not present
        if (!str_contains($envContent, '# Laravel Command Center Configuration')) {
            File::append($envFile, "\n# Laravel Command Center Configuration\n");
        }

        // Add or update each variable
        foreach ($envVars as $key => $value) {
            if (str_contains($envContent, "{$key}=")) {
                // Update existing value
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
                File::put($envFile, $envContent);
            } else {
                // Add new value
                File::append($envFile, "{$key}={$value}\n");
            }
        }
    }

    /**
     * Update the .env.example file with empty keys
     */
    private function updateEnvExampleFile(): void
    {
        $envExampleFile = base_path('.env.example');

        if (!File::exists($envExampleFile)) {
            $this->warn('.env.example file not found. Skipping .env.example update.');
            return;
        }

        $envContent = File::get($envExampleFile);

        $envKeys = [
            'LARAVEL_COMMAND_CENTER_ROUTE_PREFIX',
            'LARAVEL_COMMAND_CENTER_USERNAME',
            'LARAVEL_COMMAND_CENTER_PASSWORD',
            'LARAVEL_COMMAND_CENTER_SESSION_LIFETIME',
        ];

        // Add section header if not present
        if (!str_contains($envContent, '# Laravel Command Center Configuration')) {
            File::append($envExampleFile, "\n# Laravel Command Center Configuration\n");
        }

        // Add keys without values
        foreach ($envKeys as $key) {
            if (!str_contains($envContent, "{$key}=")) {
                File::append($envExampleFile, "{$key}=\n");
            }
        }
    }

    /**
     * Display security warnings and access information
     */
    private function displaySecurityWarnings(): void
    {
        $this->warn('⚠ IMPORTANT SECURITY NOTES:');
        $this->line('• Your credentials have been saved to .env');
        $this->line('• Make sure to keep your route prefix secret');
        $this->line('• Clear your config cache: php artisan config:clear');
        $this->newLine();

        // Try to get the configured route prefix
        $envFile = base_path('.env');
        $envContent = File::get($envFile);
        
        if (preg_match('/LARAVEL_COMMAND_CENTER_ROUTE_PREFIX=(.+)/', $envContent, $matches)) {
            $prefix = trim($matches[1]);
            $this->info('🌐 Access your Command Center at: ' . url($prefix));
        }
    }

    /**
     * Show manual instructions for publishing configuration
     */
    private function showConfigInstructions(): void
    {
        $this->newLine();
        $this->warn('⚠ Configuration not published');
        $this->line('To publish the configuration file manually, run:');
        $this->line('  php artisan vendor:publish --tag=command-center-config');
        $this->newLine();
    }

    /**
     * Show manual instructions for publishing assets
     */
    private function showAssetsInstructions(): void
    {
        $this->newLine();
        $this->warn('⚠ Assets not published');
        $this->line('To publish the compiled assets manually, run:');
        $this->line('  php artisan vendor:publish --tag=command-center-assets');
        $this->newLine();
    }

    /**
     * Show manual instructions for environment variables
     */
    private function showEnvInstructions(): void
    {
        $this->newLine();
        $this->warn('⚠ Environment variables not added');
        $this->line('Add the following keys to your .env file:');
        $this->newLine();
        $this->line('  LARAVEL_COMMAND_CENTER_ROUTE_PREFIX=command-center/your-secret-path');
        $this->line('  LARAVEL_COMMAND_CENTER_USERNAME=admin');
        $this->line('  LARAVEL_COMMAND_CENTER_PASSWORD=your-secure-password');
        $this->line('  LARAVEL_COMMAND_CENTER_SESSION_LIFETIME=120');
        $this->newLine();
    }
}
