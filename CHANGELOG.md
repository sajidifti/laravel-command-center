# Changelog

All notable changes to `laravel-command-center` will be documented in this file.

## 1.1.0 - 2025-11-21

### Added

- **Interactive Installation Wizard**: Complete rewrite of `command-center:install` command
  - Step-by-step guided installation with Y/n prompts
  - Interactive prompts for all configuration values
  - Automatic generation of secure random secrets
  - Smart defaults for all settings
- **Multiple Installation Modes**:
  - Interactive mode (default) - guides users through each step
  - Flag-based mode - use `--with-config`, `--with-assets`, `--with-env` flags
  - Complete mode - use `--complete` flag for non-interactive CI/CD installations
- **Automatic Environment Configuration**:
  - Automatically adds variables to `.env` with actual values
  - Automatically adds variables to `.env.example` with empty values
  - Random 16-character secret generation for route prefix
  - Random 16-character password generation
  - Hidden password input during interactive setup
- **Helpful Skip Instructions**: When users skip any step, the installer displays manual instructions
- **Smart Environment Variable Updates**: Detects and updates existing environment variables

### Changed

- Install command now publishes assets by default (previously skipped by default)
- Install command description updated to "Install the Laravel Command Center package (interactive)"
- Environment variable prompt now mentions both `.env` and `.env.example`
- Session directory path standardized to `storage/framework/management_sessions/`
- Improved security warnings and access URL display after installation

### Removed

- **Deprecated `command-center:publish-assets` command** - functionality merged into install command
  - Use `php artisan command-center:install --with-assets` instead
  - Or use `php artisan vendor:publish --tag=command-center-assets --force`
- Removed redundant asset publishing command from service provider registration

### Fixed

- Consistent session directory naming throughout the package
- Environment variable updates now properly handle existing keys
- Better error handling for missing `.env` and `.env.example` files

### Documentation

- Complete rewrite of installation section in README
- Added comprehensive documentation for all installation modes
- Removed all references to deprecated `publish-assets` command
- Added "Accessing the Command Center" section with security notes
- Updated troubleshooting section with current commands
- Fixed all path references to use correct session directory name

### Security

- Automatic generation of cryptographically secure random secrets
- Route prefix now includes random 16-character secret by default
- Password input is hidden during interactive installation
- Improved security warnings and best practices guidance

## 1.0.0 - 2025-11-19

### Added

- Initial release
- Database-independent management panel
- File-based session management
- Prebuilt Tailwind CSS assets (no NPM required)
- Standalone authentication system
- Artisan command execution via web interface
- Environment variable management
- Maintenance mode toggle with bypass URLs
- System information display
- Cache management commands
- Database migration tools
- Storage link management
- Installation command
- Session cleanup command
- Comprehensive documentation

### Features

- Zero database dependency - works during outages
- Plug-and-play installation
- Secure file-based authentication
- Real-time command output streaming
- Environment settings editor
- Maintenance mode management
- CSRF protection
- HTTPOnly cookies
- Customizable route prefix

### Security

- Environment-based credentials
- No database queries for auth
- Secure session handling
- Configurable route prefix for obscurity
