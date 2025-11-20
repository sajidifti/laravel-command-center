<?php

use Illuminate\Support\Facades\Route;
use Sajidifti\LaravelCommandCenter\Http\Controllers\CommandCenterAuthController;
use Sajidifti\LaravelCommandCenter\Http\Controllers\CommandCenterController;

$prefix = config('command-center.route_prefix', 'command-center/secret');

Route::prefix($prefix)
    ->middleware([
        'command-center.session',
        'command-center.errors',
    ])
    ->group(function () {
        // Guest routes (login)
        Route::middleware('command-center.guest')->group(function () {
            Route::get('/login', [CommandCenterAuthController::class, 'showLoginForm'])->name('command-center.login');
            Route::post('/login', [CommandCenterAuthController::class, 'login'])->name('command-center.login.submit');
        });

        // Authenticated routes
        Route::middleware('command-center.auth')->group(function () {
            Route::get('/', [CommandCenterController::class, 'index'])->name('command-center.index');
            Route::post('/logout', [CommandCenterAuthController::class, 'logout'])->name('command-center.logout');
            
            // System operations
            Route::post('/execute-command', [CommandCenterController::class, 'executeCommand'])->name('command-center.execute-command');
            Route::post('/unlink-storage', [CommandCenterController::class, 'unlinkStorage'])->name('command-center.unlink-storage');
            Route::get('/system-info', [CommandCenterController::class, 'getSystemInfo'])->name('command-center.system-info');
            
            // Environment management
            Route::get('/env-settings', [CommandCenterController::class, 'getEnvSettings'])->name('command-center.env-settings');
            Route::post('/env-settings', [CommandCenterController::class, 'updateEnvSettings'])->name('command-center.env-settings.update');
            Route::get('/env-file', [CommandCenterController::class, 'getEnvFile'])->name('command-center.env-file');
            Route::post('/env-file', [CommandCenterController::class, 'updateEnvFile'])->name('command-center.env-file.update');
            
            // Maintenance mode
            Route::post('/maintenance/toggle', [CommandCenterController::class, 'toggleMaintenance'])->name('command-center.maintenance.toggle');
            Route::get('/maintenance/status', [CommandCenterController::class, 'getMaintenanceStatus'])->name('command-center.maintenance.status');
        });
    });
