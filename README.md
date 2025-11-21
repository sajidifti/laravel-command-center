# Laravel Laravel Command Center

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sajidifti/laravel-command-center.svg?style=flat-square)](https://packagist.org/packages/sajidifti/laravel-command-center)
[![Total Downloads](https://img.shields.io/packagist/dt/sajidifti/laravel-command-center.svg?style=flat-square)](https://packagist.org/packages/sajidifti/laravel-command-center)

A database-independent Laravel Command Center for Laravel applications that works even when your database is down. Perfect for emergency maintenance, system management, and troubleshooting.

## Features

- ✅ **Zero Database Dependency** - Works even when database is unavailable
- ✅ **File-Based Sessions** - Independent session management
- ✅ **Prebuilt Assets** - No NPM/Vite required
- ✅ **Emergency Access** - Manage your app during outages
- ✅ **Artisan Commands** - Execute commands via web interface
- ✅ **Environment Management** - Edit .env variables through UI
- ✅ **Maintenance Mode** - Toggle with bypass URL generation
- ✅ **System Information** - View PHP, Laravel, and server details
- ✅ **Standalone Auth** - Uses .env credentials, no database needed

## Installation

### Option 1: Install from Packagist (Production)

For production use, install the package from Packagist:

```bash
composer require sajidifti/laravel-command-center
```

Then run the installation command:

```bash
php artisan command-center:install
```

The installer will guide you through an interactive setup process, asking you to:

- Publish configuration file (default: Yes)
- Publish compiled assets (default: Yes)
- Add environment variables to `.env` and `.env.example` (default: Yes)

For each environment variable, you'll be prompted with secure defaults:

- **Route Prefix**: Includes a randomly generated secret path
- **Username**: Default is `admin`
- **Password**: Randomly generated 16-character password
- **Session Lifetime**: Default is `120` minutes

### Option 2: Install for Local Development

For local development or contributing to the package:

1. **Clone or add the package to your Laravel project**:

```bash
# Create packages directory if it doesn't exist
mkdir -p packages/sajidifti

# Clone the repository
cd packages/sajidifti
git clone https://github.com/sajidifti/laravel-command-center.git
```

2. **Add the local repository to your root `composer.json`**:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/sajidifti/laravel-command-center",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "sajidifti/laravel-command-center": "@dev"
    }
}
```

3. **Install the package**:

```bash
composer require sajidifti/laravel-command-center @dev
```

4. **Run the installation command**:

```bash
php artisan command-center:install
```

### Accessing the Command Center

After installation, the command will display your access URL. Navigate to the route prefix you configured (e.g., `https://yourdomain.com/command-center/somesecret`) and login with the credentials you set during installation.

**Security Note:** Your route prefix includes a random secret path for security. Keep this URL private and use HTTPS in production.

## Available Commands

### `command-center:install`

Interactive installation wizard that guides you through setting up the Laravel Command Center.

#### Interactive Mode (Default)

Simply run the command and answer the prompts:

```bash
php artisan command-center:install
```

You'll be asked:

1. **Publish configuration file?** (yes/no) [yes]
2. **Publish compiled assets (CSS/JS)?** (yes/no) [yes]
3. **Add environment variables to .env (and .env.example)?** (yes/no) [yes]

If you choose to add environment variables, you'll be prompted for:

- **Route Prefix**: Default includes a random secret (e.g., `command-center/somesecret`)
- **Username**: Default is `admin`
- **Password**: Hidden input, defaults to a random 16-character string
- **Session Lifetime**: Default is `120` minutes

#### Flag-Based Mode

Skip interactive prompts by using flags to install only specific components:

```bash
# Publish only configuration
php artisan command-center:install --only-config

# Publish only assets
php artisan command-center:install --only-assets

# Only add environment variables
php artisan command-center:install --only-env
```

**Note:** The `--only-*` flags are mutually exclusive. When you use one, only that specific component will be installed. To install multiple components, run the command multiple times or use interactive mode.

#### Full Installation Mode (Non-Interactive)

For automated deployments or CI/CD pipelines:

```bash
php artisan command-center:install --full
```

This will:

- Publish configuration file
- Publish compiled assets
- Add environment variables with secure random defaults
- No user interaction required

#### What Happens When You Skip Steps

If you answer "no" to any step, the installer will show you manual instructions:

**Skipped Config:**

```
⚠ Configuration not published
To publish the configuration file manually, run:
  php artisan vendor:publish --tag=command-center-config
```

**Skipped Assets:**

```
⚠ Assets not published
To publish the compiled assets manually, run:
  php artisan vendor:publish --tag=command-center-assets
```

**Skipped Environment Variables:**

```
⚠ Environment variables not added
Add the following keys to your .env file:

  LARAVEL_COMMAND_CENTER_ROUTE_PREFIX=command-center/your-secret-path
  LARAVEL_COMMAND_CENTER_USERNAME=admin
  LARAVEL_COMMAND_CENTER_PASSWORD=your-secure-password
  LARAVEL_COMMAND_CENTER_SESSION_LIFETIME=120
```

### `command-center:clean-sessions`

Cleans up expired session files from the command center's file-based session storage.

```bash
php artisan command-center:clean-sessions
```

**What it does**:

- Scans `storage/framework/management_sessions/`
- Removes expired session files
- Reports number of sessions cleaned

**When to use**:

- Periodically to free up disk space
- If experiencing session-related issues
- Can be added to Laravel's scheduler for automatic cleanup

### Manual Publishing (Advanced)

You can also manually publish specific components using Laravel's vendor:publish command:

```bash
# Publish only configuration
php artisan vendor:publish --tag=command-center-config

# Publish only assets
php artisan vendor:publish --tag=command-center-assets

# Force publish (overwrites existing files)
php artisan vendor:publish --tag=command-center-assets --force
```

## Configuration

The package is configured via `config/command-center.php`. After installation, you can customize various aspects of the Command Center.

### Basic Configuration

```php
return [
    // Route prefix for accessing the Command Center
    'route_prefix' => env('LARAVEL_COMMAND_CENTER_ROUTE_PREFIX', 'command-center/secret'),
    
    // Authentication credentials
    'username' => env('LARAVEL_COMMAND_CENTER_USERNAME', 'admin'),
    'password' => env('LARAVEL_COMMAND_CENTER_PASSWORD', 'password'),
    
    // Session configuration
    'session' => [
        'lifetime' => env('LARAVEL_COMMAND_CENTER_SESSION_LIFETIME', 120),  // minutes
        'path' => storage_path('framework/management_sessions'),
        'cookie' => 'laravel_command_center_session_id',
        'gc_probability' => 2,  // 2% chance of garbage collection
    ],
];
```

### Customizable Features

The configuration file includes several customizable sections:

#### System Information Items

Customize which system information is displayed on the dashboard:

```php
'system_info_items' => [
    [
        'key' => 'php_version',
        'label' => 'PHP Version',
        'icon' => 'heroicon-o-code-bracket',
        'value' => fn() => PHP_VERSION,
    ],
    // Add your own custom system info items
],
```

#### Environment Settings Categories

Define which environment variables are editable through the UI:

```php
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
            // Add more fields
        ],
    ],
    // Add your own custom categories
],
```

#### Command Sections

Organize and customize available Artisan commands:

```php
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
            // Add more commands
        ],
    ],
    // Add your own custom sections
],
```

#### Allowed Commands

For security, only whitelisted commands can be executed:

```php
'allowed_commands' => [
    'optimize',
    'cache:clear',
    'migrate',
    // Add your custom commands here
],
```

> **Note:** Any command you add to `command_sections` must also be added to `allowed_commands` for security validation.

## Usage

### Accessing the Command Center

1. Navigate to your configured route prefix (e.g., `https://yourdomain.com/command-center/somesecret`)
2. Login with credentials from `.env`
3. Use the interface to manage your application

### Features Available

#### System Information

- PHP Version
- Laravel Version
- Environment Mode
- Debug Status
- Database Connection

#### Artisan Commands

- Optimization commands (cache, config, routes)
- Clear cache commands
- Database migrations
- Queue management
- Custom commands

#### Environment Management

- Edit .env variables through secure UI
- Grouped by category (App, Database, Mail, etc.)
- Safe updates with confirmation

#### Maintenance Mode

- Toggle maintenance mode on/off
- Generate secure bypass URLs
- Copy bypass URL to clipboard

## How It Works

### Database Independence

The Laravel Command Center is completely independent of your application's database:

1. **File-Based Sessions**: Uses its own session storage in `storage/framework/management_sessions/`
2. **No Web Middleware**: Bypasses Laravel's database-dependent middleware
3. **Standalone Routes**: Loads without web middleware group
4. **No Database Queries**: Authentication uses .env, not database

### Security Features

- ✅ Customizable route prefix
- ✅ Environment-based credentials
- ✅ File-based authentication
- ✅ HTTPOnly session cookies
- ✅ CSRF protection
- ✅ Secure password handling

## Requirements

- PHP 8.2 or higher
- Laravel 11.x or 12.x
- Write permissions for `storage/framework/management_sessions/`

## Prebuilt Assets

This package ships with prebuilt Tailwind CSS v4 in the `public` directory, so consumers do not need Node.js or NPM to use the package in production.

**Development / Rebuilding Assets**

If you need to modify the frontend and rebuild the assets, the package includes a Vite + Tailwind v4 build setup. From the package root run:

```bash
cd packages/sajidifti/laravel-command-center # or cd vendor/sajidifti/laravel-command-center
npm install
npm run build
```

The build writes compiled JS/CSS into the package `public` directory. After building, publish the package assets into your application with:

```bash
php artisan command-center:install --only-assets
# Or use Laravel's vendor:publish command
php artisan vendor:publish --tag=command-center-assets --force
```

**Notes**:

- The package `package.json` and build config are intended for package maintainers
- Built assets should be committed to the package `public` folder so consumers don't need a build step
- The build outputs are placed in `public/js` and `public/css` inside the package
- Assets are published to `public/vendor/laravel-command-center/` by the service provider

## Troubleshooting

### Command Center Not Loading

1. Check that route prefix is correct in `.env`
2. Clear config cache: `php artisan config:clear`
3. Verify storage permissions: `storage/framework/management_sessions/` needs to be writable

### Session Issues

```bash
# Clear command center sessions
php artisan command-center:clean-sessions
```

### Assets Not Loading

```bash
# Republish assets using the install command
php artisan command-center:install --only-assets

# Or use vendor:publish with force flag
php artisan vendor:publish --tag=command-center-assets --force
```

### Cannot Access During Database Outage

This is the main feature! The command center should work even when:

- Database is down
- Tables don't exist
- Connection fails
- During migrations

If it's not working, check your view composers aren't querying the database.

## Advanced Configuration

### Custom Session Path

```php
// config/command-center.php
'session' => [
    'path' => storage_path('custom/session/path'),
],
```

### Custom Middleware

Register additional middleware in `bootstrap/app.php`:

```php
$middleware->alias([
    'command-center.custom' => \App\Http\Middleware\CustomCommandCenterMiddleware::class,
]);
```

### Scheduled Session Cleanup

Add to your `routes/console.php` or scheduler:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('command-center:clean-sessions')->daily();
```

## Security Best Practices

1. **Change Default Route**: Use a unique, hard-to-guess route prefix
2. **Strong Credentials**: Use strong username and password
3. **HTTPS Only**: Always use HTTPS in production

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Md. Sajid Anam Ifti](https://github.com/sajidifti)
- [All Contributors](../../contributors)

## Support

For support, email <info@sajidifti.com> or open an issue on GitHub.
