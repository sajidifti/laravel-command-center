@extends('laravel-command-center::layout')

@section('content')
    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-4 mb-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/') }}" target="_blank"
                    class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Visit App
                </a>
                <button onclick="openBypassUrl()" id="bypass-button"
                    class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center"
                    style="display: none;">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                    Bypass Maintenance
                </button>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="openEnvSettingsModal()"
                    class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Environment Settings
                </button>
                <button onclick="quickMaintenance()" id="maintenance-toggle"
                    class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <span id="maintenance-text">Loading...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Secret Modal -->
    <div id="secret-modal" style="display: none;"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Maintenance Mode Secret</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Enter a secret bypass token to allow authorized access
                during maintenance mode. This is required for security.</p>
            <div class="relative mb-4">
                <input type="text" id="secret-input"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 pr-24"
                    placeholder="Enter secret token" required>
                <button onclick="generateSecret()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs rounded transition-colors">
                    🎲 Generate
                </button>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">💡 Tip: Use the generate button for a secure random
                token</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeSecretModal()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">Cancel</button>
                <button onclick="confirmSecret()"
                    class="px-4 py-2 bg-orange-600 dark:bg-orange-700 text-white rounded-lg hover:bg-orange-700 dark:hover:bg-orange-800 transition-colors text-sm">Confirm
                    & Copy URL</button>
            </div>
        </div>
    </div>

    <!-- Environment Settings Modal -->
    <div id="env-settings-modal" style="display: none;"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Environment Settings
                </h3>
                <button onclick="closeEnvSettingsModal()"
                    class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6" id="env-settings-container">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"></div>
                    <span class="ml-3 text-gray-500 dark:text-gray-400">Loading settings...</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeEnvSettingsModal()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                    Cancel
                </button>
                <button onclick="showSaveConfirmation()"
                    class="px-4 py-2 bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-lg transition-colors text-sm">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" style="display: none;"
        class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-60">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div
                    class="shrink-0 w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-100">Confirm Changes</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to update environment settings?
                This will modify your .env file and may affect your application's behavior.</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeConfirmationModal()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                    Cancel
                </button>
                <button onclick="confirmSaveEnvSettings()"
                    class="px-4 py-2 bg-orange-600 dark:bg-orange-700 text-white rounded-lg hover:bg-orange-700 dark:hover:bg-orange-800 transition-colors text-sm">
                    Yes, Update Settings
                </button>
            </div>
        </div>
    </div>

    <!-- System Information Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100">System Information</h2>
            <button onclick="loadSystemInfo()"
                class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Refresh
            </button>
        </div>
        <div id="system-info" class="grid grid-cols-2 md:grid-cols-3 gap-2">
            <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
                <p class="text-xs text-gray-600 dark:text-gray-400">PHP Version</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100" id="info-php">Loading...</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
                <p class="text-xs text-gray-600 dark:text-gray-400">Laravel Version</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100" id="info-laravel">Loading...</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
                <p class="text-xs text-gray-600 dark:text-gray-400">Environment</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100" id="info-env">Loading...</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
                <p class="text-xs text-gray-600 dark:text-gray-400">Debug Mode</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100" id="info-debug">Loading...</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
                <p class="text-xs text-gray-600 dark:text-gray-400">Timezone</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100" id="info-timezone">Loading...</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded p-2">
                <p class="text-xs text-gray-600 dark:text-gray-400">Database</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100" id="info-database">Loading...</p>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: Commands Left, Terminal Right -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Left Panel: Command Cards -->
        <div class="relative">
            <div id="commands-container" class="space-y-4 overflow-y-scroll rounded-lg"
                style="max-height: calc(100vh - 300px); scrollbar-width: none; -ms-overflow-style: none;">
                <!-- Optimization Commands -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Optimization Commands
                    </h2>
                    <div class="space-y-1">
                        <button onclick="executeCommand('optimize')"
                            class="w-full text-left px-3 py-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Optimize
                                Application</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">optimize</span>
                        </button>
                        <button onclick="executeCommand('optimize:clear')"
                            class="w-full text-left px-3 py-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Clear
                                Optimizations</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">optimize:clear</span>
                        </button>
                        <button onclick="executeCommand('config:cache')"
                            class="w-full text-left px-3 py-2 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Cache Config</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">config:cache</span>
                        </button>
                        <button onclick="executeCommand('route:cache')"
                            class="w-full text-left px-3 py-2 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Cache Routes</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">route:cache</span>
                        </button>
                        <button onclick="executeCommand('view:cache')"
                            class="w-full text-left px-3 py-2 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Cache Views</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">view:cache</span>
                        </button>
                    </div>
                </div>

                <!-- Clear Cache Commands -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Clear Cache Commands
                    </h2>
                    <div class="space-y-1">
                        <button onclick="executeCommand('cache:clear')"
                            class="w-full text-left px-3 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Clear Application
                                Cache</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">cache:clear</span>
                        </button>
                        <button onclick="executeCommand('config:clear')"
                            class="w-full text-left px-3 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Clear Config
                                Cache</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">config:clear</span>
                        </button>
                        <button onclick="executeCommand('route:clear')"
                            class="w-full text-left px-3 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Clear Route
                                Cache</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">route:clear</span>
                        </button>
                        <button onclick="executeCommand('view:clear')"
                            class="w-full text-left px-3 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Clear Compiled
                                Views</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">view:clear</span>
                        </button>
                    </div>
                </div>

                <!-- Database Commands -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                            </path>
                        </svg>
                        Database Commands
                    </h2>
                    <div class="space-y-1">
                        <button onclick="executeCommand('migrate')"
                            class="w-full text-left px-3 py-2 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Run Migrations</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">migrate</span>
                        </button>
                        <button onclick="executeCommand('migrate:status')"
                            class="w-full text-left px-3 py-2 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Migration
                                Status</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">migrate:status</span>
                        </button>
                        <button
                            onclick="if(confirm('This will drop all tables and re-run migrations. Continue?')) executeCommand('migrate:fresh')"
                            class="w-full text-left px-3 py-2 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Fresh Migration
                                ⚠️</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">migrate:fresh</span>
                        </button>
                        <button
                            onclick="if(confirm('This will drop all tables, re-run migrations, and seed the database. Continue?')) executeCommand('migrate:fresh --seed')"
                            class="w-full text-left px-3 py-2 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Fresh Migration + Seed
                                ⚠️</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">migrate:fresh
                                --seed</span>
                        </button>
                        <button
                            onclick="if(confirm('Rollback the last batch of migrations?')) executeCommand('migrate:rollback')"
                            class="w-full text-left px-3 py-2 bg-orange-50 dark:bg-orange-900/30 hover:bg-orange-100 dark:hover:bg-orange-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Rollback
                                Migration</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">migrate:rollback</span>
                        </button>
                        <button onclick="executeCommand('db:seed')"
                            class="w-full text-left px-3 py-2 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Run Database
                                Seeder</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">db:seed</span>
                        </button>
                    </div>
                </div>

                <!-- Other Commands -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Other Commands
                    </h2>
                    <div class="space-y-1">
                        <button onclick="executeCommand('storage:link')"
                            class="w-full text-left px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Create Storage
                                Link</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">storage:link</span>
                        </button>
                        <button onclick="unlinkStorage()"
                            class="w-full text-left px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Remove Storage
                                Link</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">storage:unlink</span>
                        </button>
                        <button onclick="executeCommand('queue:restart')"
                            class="w-full text-left px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Restart Queue
                                Workers</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">queue:restart</span>
                        </button>
                    </div>
                </div>

                <!-- Command Center Commands -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Command Center Commands
                    </h2>
                    <div class="space-y-1">
                        <button onclick="executeCommand('command-center:clean-sessions')"
                            class="w-full text-left px-3 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded transition-colors group">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 block">Clean Expired
                                Sessions</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">command-center:clean-sessions</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Custom Scrollbar -->
            <div id="commands-scrollbar"
                class="absolute right-0 top-0 w-1 bg-transparent rounded-full transition-opacity duration-200 opacity-0 hover:opacity-100"
                style="height: calc(100vh - 300px); pointer-events: auto;">
                <div id="commands-scrollbar-thumb"
                    class="w-full bg-gray-400 rounded-full cursor-pointer hover:bg-gray-500"
                    style="height: 100px; will-change: transform;"></div>
            </div>
        </div>

        <!-- Right Panel: Command Output Terminal -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex flex-col"
            style="max-height: calc(100vh - 300px);">
            <div class="flex items-center justify-between mb-3 shrink-0">
                <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Command Output
                </h2>
                <button onclick="clearOutput()"
                    class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">Clear</button>
            </div>
            <div class="relative flex-1 min-h-0">
                <div id="command-output"
                    class="bg-gray-50 dark:bg-black text-gray-900 dark:text-green-300 rounded p-3 font-mono text-xs h-full wrap-break-word border border-gray-200 dark:border-gray-900"
                    style="overflow-y: scroll; overflow-x: hidden; word-wrap: break-word; white-space: pre-wrap; scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="text-gray-500">Ready to execute commands...</div>
                </div>
                <!-- Custom Terminal Scrollbar -->
                <div id="terminal-scrollbar"
                    class="absolute right-1 top-0 bottom-0 w-1 bg-transparent rounded-full transition-opacity duration-200 opacity-0"
                    style="pointer-events: auto;">
                    <div id="terminal-scrollbar-thumb" class="w-full rounded-full cursor-pointer"
                        style="height: 100px; will-change: transform; background: rgba(34, 197, 94, 0.3);"></div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Hide all native scrollbars */
            #commands-container::-webkit-scrollbar,
            #env-settings-container::-webkit-scrollbar {
                width: 0;
                height: 0;
                display: none;
            }

            #commands-container,
            #env-settings-container {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            /* Environment settings container styling */
            #env-settings-container::-webkit-scrollbar {
                width: 4px;
            }

            #env-settings-container::-webkit-scrollbar-track {
                background: transparent;
            }

            #env-settings-container::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, 0.3);
                border-radius: 10px;
            }

            #env-settings-container::-webkit-scrollbar-thumb:hover {
                background: rgba(148, 163, 184, 0.5);
            }

            #env-settings-container {
                scrollbar-width: thin;
                scrollbar-color: rgba(148, 163, 184, 0.3) transparent;
            }

            /* Hide terminal scrollbar */
            #command-output::-webkit-scrollbar {
                display: none;
            }

            #command-output {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            let maintenanceStatus = 'up';

            // Load system information on page load
            $(document).ready(function() {
                loadSystemInfo();
                loadMaintenanceStatus();
            });

            function loadSystemInfo() {
                $.ajax({
                    url: "{{ route('command-center.system-info') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#info-php').text(data.data.php_version);
                            $('#info-laravel').text(data.data.laravel_version);
                            $('#info-env').text(data.data.environment);
                            $('#info-debug').text(data.data.debug_mode);
                            $('#info-timezone').text(data.data.timezone);
                            $('#info-database').text(data.data.database_connection);
                        }
                    },
                    error: function(error) {
                        console.error('Error loading system info:', error);
                    }
                });
            }

            function loadMaintenanceStatus() {
                $.ajax({
                    url: "{{ route('command-center.maintenance.status') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            maintenanceStatus = data.status;

                            // Restore secret from server if maintenance is down
                            if (data.status === 'down' && data.secret) {
                                window.lastMaintenanceSecret = data.secret;
                                window.currentBypassUrl = '{{ url('/') }}' + '/' + data.secret;
                            } else {
                                window.lastMaintenanceSecret = null;
                                window.currentBypassUrl = null;
                            }

                            updateMaintenanceButton();
                        }
                    },
                    error: function(error) {
                        console.error('Error loading maintenance status:', error);
                    }
                });
            }

            function updateMaintenanceButton() {
                const $button = $('#maintenance-toggle');
                const $text = $('#maintenance-text');
                const $bypassButton = $('#bypass-button');

                if (maintenanceStatus === 'down') {
                    // App is DOWN = Maintenance is ON = Show RED button that says "Turn OFF"
                    $text.text('Maintenance: ON - Click to Turn OFF');
                    $button.attr('class',
                        'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition-colors flex items-center'
                    );
                    // Show bypass button when maintenance is on
                    if (window.lastMaintenanceSecret) {
                        $bypassButton.css('display', 'flex');
                    }
                } else {
                    // App is UP = Maintenance is OFF = Show ORANGE button that says "Turn ON"
                    $text.text('Maintenance: OFF - Click to Turn ON');
                    $button.attr('class',
                        'px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-xs font-medium transition-colors flex items-center'
                    );
                    // Hide bypass button when maintenance is off
                    $bypassButton.css('display', 'none');
                }
            }

            function openBypassUrl() {
                if (window.currentBypassUrl) {
                    window.open(window.currentBypassUrl, '_blank');
                } else if (window.lastMaintenanceSecret) {
                    const bypassUrl = '{{ url('/') }}' + '/' + window.lastMaintenanceSecret;
                    window.open(bypassUrl, '_blank');
                }
            }

            let pendingMaintenanceAction = null;

            function quickMaintenance() {
                const action = maintenanceStatus === 'down' ? 'up' : 'down';
                pendingMaintenanceAction = action;

                if (action === 'down') {
                    // Show modal for secret input
                    $('#secret-modal').css('display', 'flex');
                    $('#secret-input').focus();
                } else {
                    // Turn maintenance off (no secret needed)
                    toggleMaintenanceMode(action, null);
                }
            }

            function closeSecretModal() {
                $('#secret-modal').css('display', 'none');
                $('#secret-input').val('');
                pendingMaintenanceAction = null;
            }

            function generateSecret() {
                // Generate a random 32-character secret token
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let secret = '';
                for (let i = 0; i < 32; i++) {
                    secret += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                $('#secret-input').val(secret);
            }

            function confirmSecret() {
                const secret = $('#secret-input').val().trim();

                if (!secret) {
                    showNotification('Secret token is required for security!', 'error');
                    return;
                }

                const action = pendingMaintenanceAction; // Save it before closing modal
                closeSecretModal();
                toggleMaintenanceMode(action, secret);
            }

            function toggleMaintenanceMode(action, secret) {
                $.ajax({
                    url: "{{ route('command-center.maintenance.toggle') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({
                        action,
                        secret
                    }),
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            // If turning maintenance ON and secret exists, copy bypass URL
                            if (data.status === 'down' && data.secret) {
                                window.lastMaintenanceSecret = data.secret; // Store for later use
                                const bypassUrl = '{{ url('/') }}' + '/' + data.secret;
                                window.currentBypassUrl = bypassUrl;
                                navigator.clipboard.writeText(bypassUrl).then(() => {
                                    showNotification(
                                        '✓ Maintenance mode ON! Bypass URL copied to clipboard.',
                                        'success');
                                }).catch(() => {
                                    showNotification(data.message + ' Bypass URL: ' + bypassUrl, 'success');
                                });
                            } else {
                                window.lastMaintenanceSecret = null;
                                window.currentBypassUrl = null;
                                showNotification(data.message, 'success');
                            }
                            maintenanceStatus = data.status;
                            updateMaintenanceButton();
                        } else {
                            showNotification('Error: ' + data.message, 'error');
                        }
                    },
                    error: function(error) {
                        console.error('Fetch error:', error);
                        showNotification('Error: ' + error.message, 'error');
                    }
                });
            }

            function showMaintenanceMessage() {
                // Prevent multiple overlays
                if ($('#maintenance-overlay').length) return;

                // Show maintenance mode message overlay
                const $overlay = $('<div>').attr('id', 'maintenance-overlay').addClass(
                    'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50');
                $overlay.html(`
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-md mx-4 text-center">
                <div class="text-6xl mb-4">🔧</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Application is in Maintenance Mode</h2>
                <p class="text-gray-600 mb-6">The main application is currently down for maintenance. However, you can still access it using the bypass URL.</p>
                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-700 font-mono break-all" id="bypass-url-display">` + (window
                    .lastMaintenanceSecret ? '{{ url('/') }}' + '/' + window.lastMaintenanceSecret : 'Loading...'
                ) + `</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="copyBypassUrl()" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                        📋 Copy URL
                    </button>
                    <button onclick="goToBypassUrl()" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm">
                        🔗 Go to App
                    </button>
                </div>
                <button onclick="$('#maintenance-overlay').remove()" class="mt-4 text-sm text-gray-500 hover:text-gray-700">
                    Close
                </button>
            </div>
        `);
                $('body').append($overlay);

                // Update bypass URL if available
                if (window.lastMaintenanceSecret) {
                    const bypassUrl = '{{ url('/') }}' + '/' + window.lastMaintenanceSecret;
                    window.currentBypassUrl = bypassUrl;
                }
            }

            function copyBypassUrl() {
                if (window.currentBypassUrl) {
                    navigator.clipboard.writeText(window.currentBypassUrl).then(() => {
                        showNotification('Bypass URL copied to clipboard!', 'success');
                    });
                }
            }

            function goToBypassUrl() {
                if (window.currentBypassUrl) {
                    window.open(window.currentBypassUrl, '_blank');
                }
            }

            function showNotification(message, type) {
                const $notification = $('<div>').text(message);
                if (type === 'success') {
                    $notification.addClass(
                        'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in');
                } else {
                    $notification.addClass(
                        'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in');
                }
                $('body').append($notification);
                setTimeout(() => {
                    $notification.remove();
                }, 3000);
            }

            // Environment Settings Modal Functions
            function openEnvSettingsModal() {
                $('#env-settings-modal').css('display', 'flex');
                loadEnvSettings();
            }

            function closeEnvSettingsModal() {
                $('#env-settings-modal').css('display', 'none');
            }

            function loadEnvSettings() {
                const $container = $('#env-settings-container');
                $container.html(`
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-500">Loading settings...</span>
            </div>
        `);

                $.ajax({
                    url: "{{ route('command-center.env-settings') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            renderEnvSettings(data.data);
                        } else {
                            $container.html('<p class="text-red-500 text-center py-8">Failed to load settings</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error loading settings:', error);
                        $container.html('<p class="text-red-500 text-center py-8">Error loading settings</p>');
                    }
                });
            }

            function renderEnvSettings(settings) {
                const $container = $('#env-settings-container');
                $container.html('');

                const categories = {
                    'app': {
                        title: 'Application Settings',
                        icon: '🔧'
                    },
                    'database': {
                        title: 'Database Settings',
                        icon: '💾'
                    },
                    'mail': {
                        title: 'Mail Settings',
                        icon: '📧'
                    },
                    'cache': {
                        title: 'Cache Settings',
                        icon: '⚡'
                    },
                    'queue': {
                        title: 'Queue Settings',
                        icon: '📋'
                    },
                    'session': {
                        title: 'Session Settings',
                        icon: '🔑'
                    }
                };

                let html = '<div class="space-y-6">';

                Object.keys(categories).forEach(category => {
                    if (!settings[category]) return;

                    html += `
                <div class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                        <span class="mr-2">${categories[category].icon}</span>
                        ${categories[category].title}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            `;

                    Object.keys(settings[category]).forEach(key => {
                        const value = settings[category][key] || '';
                        const isPassword = key.includes('PASSWORD');
                        const inputType = isPassword ? 'password' : 'text';

                        html += `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">${key}</label>
                        <input 
                            type="${inputType}"
                            id="${key}"
                            value="${escapeHtml(String(value))}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400"
                            ${isPassword ? 'placeholder="••••••••"' : ''}
                        >
                    </div>
                `;
                    });

                    html += `
                    </div>
                </div>
            `;
                });

                html += '</div>';
                $container.html(html);
            }

            function showSaveConfirmation() {
                $('#confirmation-modal').css('display', 'flex');
            }

            function closeConfirmationModal() {
                $('#confirmation-modal').css('display', 'none');
            }

            function confirmSaveEnvSettings() {
                closeConfirmationModal();
                saveEnvSettings();
            }

            function saveEnvSettings() {
                const settings = {};
                $('#env-settings-container input').each(function() {
                    if ($(this).val()) {
                        settings[$(this).attr('id')] = $(this).val();
                    }
                });

                $.ajax({
                    url: "{{ route('command-center.env-settings.update') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({
                        settings
                    }),
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            closeEnvSettingsModal();
                        } else {
                            showNotification('Error: ' + data.message, 'error');
                        }
                    },
                    error: function(error) {
                        showNotification('Error: ' + error.message, 'error');
                    }
                });
            }

            function executeCommand(command) {
                appendOutput(
                    `<span class="text-yellow-600 dark:text-yellow-400">[${getCurrentTime()}]</span> <span class="text-blue-600 dark:text-blue-400">Executing:</span> php artisan ${command}`
                );
                appendOutput(
                    `<span class="text-gray-400 dark:text-gray-500">───────────────────────────────────────────────</span>`);

                // Show loading indicator
                const $button = $(event.target).closest('button');
                $button.prop('disabled', true);
                $button.addClass('opacity-50 cursor-not-allowed');

                // Use fetch with POST for Server-Sent Events
                fetch("{{ route('command-center.execute-command') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'text/event-stream'
                    },
                    body: JSON.stringify({
                        command: command
                    })
                }).then(response => {
                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();

                    function readStream() {
                        reader.read().then(({
                            done,
                            value
                        }) => {
                            if (done) {
                                $button.prop('disabled', false);
                                $button.removeClass('opacity-50 cursor-not-allowed');
                                return;
                            }

                            const chunk = decoder.decode(value);
                            const lines = chunk.split('\n');

                            lines.forEach(line => {
                                if (line.startsWith('data: ')) {
                                    try {
                                        const data = JSON.parse(line.substring(6));

                                        switch (data.type) {
                                            case 'start':
                                                appendOutput(
                                                    `<span class="text-cyan-600 dark:text-cyan-400">⚡ Command started...</span>`
                                                );
                                                break;

                                            case 'output':
                                                // Stream output in real-time
                                                if (data.message && data.message.trim()) {
                                                    appendOutput(
                                                        `<span class="text-gray-700 dark:text-green-400">${escapeHtml(data.message)}</span>`
                                                    );
                                                }
                                                break;

                                            case 'complete':
                                                if (data.success) {
                                                    appendOutput(
                                                        `<span class="text-green-600 dark:text-green-500">[${getCurrentTime()}]</span> <span class="text-green-600 dark:text-green-400">✓ Command completed successfully (Exit Code: ${data.exit_code})</span>`
                                                    );
                                                } else {
                                                    appendOutput(
                                                        `<span class="text-red-600 dark:text-red-500">[${getCurrentTime()}]</span> <span class="text-red-600 dark:text-red-400">✗ Command failed (Exit Code: ${data.exit_code})</span>`
                                                    );
                                                }
                                                appendOutput('');
                                                $button.prop('disabled', false);
                                                $button.removeClass('opacity-50 cursor-not-allowed');
                                                break;

                                            case 'error':
                                                appendOutput(
                                                    `<span class="text-red-600 dark:text-red-400">Error: ${escapeHtml(data.message)}</span>`
                                                );
                                                appendOutput(
                                                    `<span class="text-red-600 dark:text-red-500">[${getCurrentTime()}]</span> <span class="text-red-600 dark:text-red-400">✗ Command failed</span>`
                                                );
                                                appendOutput('');
                                                $button.prop('disabled', false);
                                                $button.removeClass('opacity-50 cursor-not-allowed');
                                                break;
                                        }
                                    } catch (e) {
                                        // Ignore parse errors for incomplete chunks
                                    }
                                }
                            });

                            readStream();
                        });
                    }

                    readStream();
                }).catch(error => {
                    appendOutput(`<span class="text-red-600 dark:text-red-400">Connection error occurred</span>`);
                    appendOutput(
                        `<span class="text-red-600 dark:text-red-500">[${getCurrentTime()}]</span> <span class="text-red-600 dark:text-red-400">✗ Request failed</span>`
                    );
                    appendOutput('');
                    $button.prop('disabled', false);
                    $button.removeClass('opacity-50 cursor-not-allowed');
                });
            }

            function unlinkStorage() {
                appendOutput(
                    `<span class="text-yellow-600 dark:text-yellow-400">[${getCurrentTime()}]</span> <span class="text-blue-600 dark:text-blue-400">Executing:</span> Remove storage link`
                );
                appendOutput(
                    `<span class="text-gray-400 dark:text-gray-500">───────────────────────────────────────────────</span>`);

                $.ajax({
                    url: "{{ route('command-center.unlink-storage') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            appendOutput(
                                `<span class="text-gray-700 dark:text-green-400">${escapeHtml(data.output)}</span>`
                            );
                            appendOutput(
                                `<span class="text-green-600 dark:text-green-500">[${getCurrentTime()}]</span> <span class="text-green-600 dark:text-green-400">✓ Command completed successfully</span>`
                            );
                        } else {
                            appendOutput(
                                `<span class="text-yellow-700 dark:text-yellow-400">${escapeHtml(data.output)}</span>`
                            );
                            appendOutput(
                                `<span class="text-yellow-600 dark:text-yellow-500">[${getCurrentTime()}]</span> <span class="text-yellow-600 dark:text-yellow-400">⚠ Command completed with warnings</span>`
                            );
                        }
                        appendOutput('');
                    },
                    error: function(error) {
                        appendOutput(`<span class="text-red-600 dark:text-red-400">Error: ${error.message}</span>`);
                        appendOutput(
                            `<span class="text-red-600 dark:text-red-500">[${getCurrentTime()}]</span> <span class="text-red-600 dark:text-red-400">✗ Request failed</span>`
                        );
                        appendOutput('');
                    }
                });
            }

            function appendOutput(text) {
                const $outputDiv = $('#command-output');
                const $line = $('<div>').html(text);
                $outputDiv.append($line);
                $outputDiv.scrollTop($outputDiv[0].scrollHeight);
            }

            function clearOutput() {
                $('#command-output').html('<div class="text-gray-500">Ready to execute commands...</div>');
            }

            function getCurrentTime() {
                const now = new Date();
                return now.toLocaleTimeString('en-US', {
                    hour12: false
                });
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            // Custom Scrollbar Implementation
            $(document).ready(function() {
                const $container = $('#commands-container');
                const $scrollbar = $('#commands-scrollbar');
                const $thumb = $('#commands-scrollbar-thumb');

                // Function to update scrollbar colors based on theme
                function updateScrollbarTheme() {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        $thumb.css('background', 'rgba(148, 163, 184, 0.5)');
                    } else {
                        $thumb.css('background', 'rgba(156, 163, 175, 0.5)');
                    }
                }

                if ($container.length && $scrollbar.length) {
                    let isDragging = false;
                    let startY = 0;
                    let startScrollTop = 0;
                    let rafId = null;

                    function updateScrollbar() {
                        if (rafId) {
                            cancelAnimationFrame(rafId);
                        }

                        rafId = requestAnimationFrame(function() {
                            const containerHeight = $container.height();
                            const contentHeight = $container[0].scrollHeight;
                            const scrollTop = $container.scrollTop();

                            // Calculate thumb height and position
                            const thumbHeight = Math.max((containerHeight / contentHeight) * containerHeight,
                                30);
                            const thumbTop = (scrollTop / contentHeight) * containerHeight;

                            $thumb.css({
                                height: thumbHeight + 'px',
                                transform: `translateY(${thumbTop}px)`,
                                transition: isDragging ? 'none' : 'transform 0ms'
                            });

                            // Show/hide scrollbar based on content
                            if (contentHeight > containerHeight) {
                                $scrollbar.css('opacity', '0.3');
                            } else {
                                $scrollbar.css('opacity', '0');
                            }

                            rafId = null;
                        });
                    }

                    // Update on scroll with immediate response
                    $container.on('scroll', updateScrollbar);

                    // Show scrollbar on hover
                    $container.on('mouseenter', function() {
                        if ($container[0].scrollHeight > $container.height()) {
                            $scrollbar.css('opacity', '0.6');
                        }
                    });

                    $container.on('mouseleave', function() {
                        if (!isDragging && $container[0].scrollHeight > $container.height()) {
                            $scrollbar.css('opacity', '0.3');
                        }
                    });

                    $scrollbar.on('mouseenter', function() {
                        $scrollbar.css('opacity', '1');
                    });

                    $scrollbar.on('mouseleave', function() {
                        if (!isDragging) {
                            $scrollbar.css('opacity', '0.3');
                        }
                    });

                    // Drag functionality
                    $thumb.on('mousedown', function(e) {
                        isDragging = true;
                        startY = e.clientY;
                        startScrollTop = $container.scrollTop();
                        $scrollbar.css('opacity', '1');
                        e.preventDefault();
                    });

                    $(document).on('mousemove', function(e) {
                        if (isDragging) {
                            const deltaY = e.clientY - startY;
                            const containerHeight = $container.height();
                            const contentHeight = $container[0].scrollHeight;
                            const scrollRatio = contentHeight / containerHeight;

                            $container.scrollTop(startScrollTop + (deltaY * scrollRatio));
                        }
                    });

                    $(document).on('mouseup', function() {
                        if (isDragging) {
                            isDragging = false;
                            $scrollbar.css('opacity', '0.3');
                        }
                    });

                    // Click on scrollbar track
                    $scrollbar.on('click', function(e) {
                        if (e.target === $scrollbar[0]) {
                            const clickY = e.clientY - $scrollbar.offset().top;
                            const containerHeight = $container.height();
                            const contentHeight = $container[0].scrollHeight;

                            $container.scrollTop((clickY / containerHeight) * contentHeight);
                        }
                    });

                    // Initial update
                    updateScrollbar();
                    updateScrollbarTheme();

                    // Update on window resize
                    $(window).on('resize', updateScrollbar);

                    // Listen for theme changes
                    const observer = new MutationObserver(updateScrollbarTheme);
                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }

                // Terminal Custom Scrollbar Implementation
                const $terminal = $('#command-output');
                const $terminalScrollbar = $('#terminal-scrollbar');
                const $terminalThumb = $('#terminal-scrollbar-thumb');

                // Function to update terminal scrollbar colors based on theme
                function updateTerminalScrollbarTheme() {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        $terminalThumb.css('background', 'rgba(34, 197, 94, 0.4)');
                    } else {
                        $terminalThumb.css('background', 'rgba(34, 197, 94, 0.3)');
                    }
                }

                if ($terminal.length && $terminalScrollbar.length) {
                    let isTerminalDragging = false;
                    let terminalStartY = 0;
                    let terminalStartScrollTop = 0;
                    let terminalRafId = null;

                    function updateTerminalScrollbar() {
                        if (terminalRafId) {
                            cancelAnimationFrame(terminalRafId);
                        }

                        terminalRafId = requestAnimationFrame(function() {
                            const containerHeight = $terminal.height();
                            const contentHeight = $terminal[0].scrollHeight;
                            const scrollTop = $terminal.scrollTop();

                            // Calculate thumb height and position
                            const thumbHeight = Math.max((containerHeight / contentHeight) * containerHeight,
                                30);
                            const thumbTop = (scrollTop / contentHeight) * containerHeight;

                            $terminalThumb.css({
                                height: thumbHeight + 'px',
                                transform: `translateY(${thumbTop}px)`,
                                transition: isTerminalDragging ? 'none' : 'transform 0ms'
                            });

                            // Show/hide scrollbar based on content
                            if (contentHeight > containerHeight) {
                                $terminalScrollbar.css('opacity', '0.3');
                            } else {
                                $terminalScrollbar.css('opacity', '0');
                            }

                            terminalRafId = null;
                        });
                    }

                    // Update on scroll with immediate response
                    $terminal.on('scroll', updateTerminalScrollbar);

                    // Show scrollbar on hover
                    $terminal.on('mouseenter', function() {
                        if ($terminal[0].scrollHeight > $terminal.height()) {
                            $terminalScrollbar.css('opacity', '0.5');
                        }
                    });

                    $terminal.on('mouseleave', function() {
                        if (!isTerminalDragging && $terminal[0].scrollHeight > $terminal.height()) {
                            $terminalScrollbar.css('opacity', '0.3');
                            updateTerminalScrollbarTheme();
                        }
                    });

                    $terminalScrollbar.on('mouseenter', function() {
                        $terminalScrollbar.css('opacity', '0.8');
                        const isDark = document.documentElement.classList.contains('dark');
                        $terminalThumb.css('background', isDark ? 'rgba(34, 197, 94, 0.6)' :
                            'rgba(34, 197, 94, 0.5)');
                    });

                    $terminalScrollbar.on('mouseleave', function() {
                        if (!isTerminalDragging) {
                            $terminalScrollbar.css('opacity', '0.3');
                            updateTerminalScrollbarTheme();
                        }
                    });

                    // Drag functionality
                    $terminalThumb.on('mousedown', function(e) {
                        isTerminalDragging = true;
                        terminalStartY = e.clientY;
                        terminalStartScrollTop = $terminal.scrollTop();
                        $terminalScrollbar.css('opacity', '0.8');
                        $terminalThumb.css('background', 'rgba(34, 197, 94, 0.6)');
                        e.preventDefault();
                    });

                    $(document).on('mousemove', function(e) {
                        if (isTerminalDragging) {
                            const deltaY = e.clientY - terminalStartY;
                            const containerHeight = $terminal.height();
                            const contentHeight = $terminal[0].scrollHeight;
                            const scrollRatio = contentHeight / containerHeight;

                            $terminal.scrollTop(terminalStartScrollTop + (deltaY * scrollRatio));
                        }
                    });

                    $(document).on('mouseup', function() {
                        if (isTerminalDragging) {
                            isTerminalDragging = false;
                            $terminalScrollbar.css('opacity', '0.3');
                            $terminalThumb.css('background', 'rgba(34, 197, 94, 0.3)');
                        }
                    });

                    // Click on scrollbar track
                    $terminalScrollbar.on('click', function(e) {
                        if (e.target === $terminalScrollbar[0]) {
                            const clickY = e.clientY - $terminalScrollbar.offset().top;
                            const containerHeight = $terminal.height();
                            const contentHeight = $terminal[0].scrollHeight;

                            $terminal.scrollTop((clickY / containerHeight) * contentHeight);
                        }
                    });

                    // Initial update
                    updateTerminalScrollbar();
                    updateTerminalScrollbarTheme();

                    // Update on window resize
                    $(window).on('resize', updateTerminalScrollbar);

                    // Update when new content is added (observe mutations)
                    const terminalObserver = new MutationObserver(updateTerminalScrollbar);
                    terminalObserver.observe($terminal[0], {
                        childList: true,
                        subtree: true
                    });

                    // Listen for theme changes
                    const terminalThemeObserver = new MutationObserver(updateTerminalScrollbarTheme);
                    terminalThemeObserver.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
            });
        </script>
    @endpush
@endsection
