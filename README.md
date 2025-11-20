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

This will:
- Publish the configuration file to `config/command-center.php`
- Publish compiled assets to `public/vendor/laravel-command-center/`
- Display setup instructions

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

### Step 3: Configure Environment

Add these variables to your `.env` file:

```env
# Laravel Command Center Configuration
LARAVEL_COMMAND_CENTER_ROUTE_PREFIX=command-center/secret
LARAVEL_COMMAND_CENTER_USERNAME=admin
LARAVEL_COMMAND_CENTER_PASSWORD=your-secure-password
LARAVEL_COMMAND_CENTER_SESSION_LIFETIME=120
```

**Important:** Change `LARAVEL_COMMAND_CENTER_ROUTE_PREFIX` to something hard to guess for security!

### Step 4: Access the Command Center

Navigate to: `https://yourdomain.com/command-center/secret` (or your custom prefix)

## Available Commands

### `command-center:install`

Installs the Laravel Command Center by publishing configuration and optionally publishing assets.

```bash
# Install with assets (recommended)
php artisan command-center:install

# Install without assets (config only)
php artisan command-center:install --no-assets
```

**What it does**:
- Publishes `config/command-center.php`
- Publishes compiled CSS/JS to `public/vendor/laravel-command-center/` (if `--no-assets` not specified)
- Displays next steps and access URL

### `command-center:publish-assets`

Force publishes the compiled frontend assets, overwriting any existing files. Useful when updating the package or if assets are corrupted.

```bash
php artisan command-center:publish-assets
```

**What it does**:
- Force copies `public/css/app.css` to `public/vendor/laravel-command-center/css/app.css`
- Force copies `public/js/app.js` to `public/vendor/laravel-command-center/js/app.js`
- Always overwrites existing files (no confirmation needed)

**When to use**:
- After updating the package to get latest UI changes
- If assets are missing or corrupted
- After rebuilding assets during development

### `command-center:clean-sessions`

Cleans up expired session files from the command center's file-based session storage.

```bash
php artisan command-center:clean-sessions
```

**What it does**:
- Scans `storage/framework/command_center_sessions/`
- Removes expired session files
- Reports number of sessions cleaned

**When to use**:
- Periodically to free up disk space
- If experiencing session-related issues
- Can be added to Laravel's scheduler for automatic cleanup

### Manual Publishing (Advanced)

You can also manually publish specific components:

```bash
# Publish only configuration
php artisan vendor:publish --tag=command-center-config

# Publish only assets
php artisan vendor:publish --tag=command-center-assets

# Force publish (overwrites existing files)
php artisan vendor:publish --tag=command-center-assets --force
```

## Configuration

The package is configured via `config/command-center.php`:

```php
return [
    'route_prefix' => env('LARAVEL_COMMAND_CENTER_ROUTE_PREFIX', 'command-center/secret'),
    'username' => env('LARAVEL_COMMAND_CENTER_USERNAME', 'admin'),
    'password' => env('LARAVEL_COMMAND_CENTER_PASSWORD', 'password'),
    'session' => [
        'driver' => 'file',
        'lifetime' => env('LARAVEL_COMMAND_CENTER_SESSION_LIFETIME', 120),
        'path' => storage_path('framework/management_sessions'),
        'cookie' => 'laravel_command_center_session_id',
    ],
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
```

## Usage

### Accessing the Command Center

1. Navigate to your configured route prefix (e.g., `https://yourdomain.com/command-center/secret`)
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

1. **File-Based Sessions**: Uses its own session storage in `storage/framework/command_center_sessions/`
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
- Write permissions for `storage/framework/command_center_sessions/`

## Prebuilt Assets

This package ships with prebuilt Tailwind CSS v4 in the `public` directory, so consumers do not need Node.js or NPM to use the package in production.

**Development / Rebuilding Assets**

If you need to modify the frontend and rebuild the assets, the package includes a Vite + Tailwind v4 build setup. From the package root run:

```bash
cd packages/sajidifti/laravel-command-center
npm install
npm run build
```

The build writes compiled JS/CSS into the package `public` directory. After building, publish the package assets into your application with:

```bash
php artisan command-center:publish-assets
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
3. Verify storage permissions: `storage/framework/command_center_sessions/` needs to be writable

### Session Issues

```bash
# Clear command center sessions
php artisan command-center:clean-sessions
```

### Assets Not Loading

```bash
# Force republish assets
php artisan command-center:publish-assets
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
4. **IP Whitelist**: Consider adding IP restrictions
5. **Regular Audits**: Monitor access logs

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Sajid Ifti](https://github.com/sajidifti)
- [All Contributors](../../contributors)

## Support

For support, email <info@sajidifti.com> or open an issue on GitHub.
