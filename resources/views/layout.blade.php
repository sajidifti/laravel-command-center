<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Command Center</title>
    @if (file_exists(public_path('vendor/laravel-command-center/css/app.css')))
        <link rel="stylesheet" href="{{ asset('vendor/laravel-command-center/css/app.css') }}">
    @else
        @vite(['resources/css/app.css'])
    @endif
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        // Dark Mode Implementation - Run immediately before page loads
        (function() {
            // Get theme from localStorage or default to system
            function getStoredTheme() {
                return localStorage.getItem('theme') || 'system';
            }

            // Get system preference
            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            // Apply theme immediately to prevent flash
            const storedTheme = getStoredTheme();
            const effectiveTheme = storedTheme === 'system' ? getSystemTheme() : storedTheme;

            if (effectiveTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 h-full m-0 p-0 transition-colors duration-200">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header
            class="bg-white dark:bg-black text-gray-800 dark:text-white shadow-lg border-b border-gray-200 dark:border-gray-900">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h1 class="text-xl font-bold">Laravel Command Center</h1>
                    </div>
                    @if (command_center_session('authenticated'))
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ command_center_session('username') }}
                            </span>
                            <!-- Dark Mode Toggle -->
                            <button type="button" id="theme-toggle"
                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                title="Toggle theme">
                                <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <svg id="theme-icon-dark" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                    </path>
                                </svg>
                                <svg id="theme-icon-system" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('command-center.logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-sm bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto px-4 py-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer
            class="bg-white dark:bg-black text-gray-600 dark:text-gray-600 py-4 border-t border-gray-200 dark:border-gray-900">
            <div class="container mx-auto px-4 text-center text-sm">
                <p>&copy; {{ date('Y') }} System Management. Use with caution.</p>
            </div>
        </footer>
    </div>

    <script>
        // Dark Mode Toggle Functionality
        (function() {
            const themes = ['light', 'dark', 'system'];

            // Get theme from localStorage or default to system
            function getStoredTheme() {
                return localStorage.getItem('theme') || 'system';
            }

            // Set theme in localStorage
            function setStoredTheme(theme) {
                localStorage.setItem('theme', theme);
            }

            // Get system preference
            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            // Apply theme to document
            function applyTheme(theme) {
                const effectiveTheme = theme === 'system' ? getSystemTheme() : theme;
                const htmlElement = document.documentElement;

                if (effectiveTheme === 'dark') {
                    htmlElement.classList.add('dark');
                } else {
                    htmlElement.classList.remove('dark');
                }

                // Update icon visibility
                updateThemeIcon(theme);
            }

            // Update the theme icon based on current theme
            function updateThemeIcon(theme) {
                const lightIcon = document.getElementById('theme-icon-light');
                const darkIcon = document.getElementById('theme-icon-dark');
                const systemIcon = document.getElementById('theme-icon-system');

                if (lightIcon && darkIcon && systemIcon) {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.add('hidden');
                    systemIcon.classList.add('hidden');

                    if (theme === 'light') {
                        lightIcon.classList.remove('hidden');
                    } else if (theme === 'dark') {
                        darkIcon.classList.remove('hidden');
                    } else {
                        systemIcon.classList.remove('hidden');
                    }
                }
            }

            // Cycle through themes
            function cycleTheme() {
                const currentTheme = getStoredTheme();
                const currentIndex = themes.indexOf(currentTheme);
                const nextIndex = (currentIndex + 1) % themes.length;
                const newTheme = themes[nextIndex];

                setStoredTheme(newTheme);
                applyTheme(newTheme);
            }

            // Initialize theme on page load
            const initialTheme = getStoredTheme();
            applyTheme(initialTheme);

            // Set up theme toggle button - wait for DOM
            function initToggle() {
                const themeToggle = document.getElementById('theme-toggle');

                if (themeToggle) {
                    // Remove any existing listeners by cloning
                    const newToggle = themeToggle.cloneNode(true);
                    themeToggle.parentNode.replaceChild(newToggle, themeToggle);

                    // Add click listener
                    newToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        cycleTheme();
                    });

                    // Initial icon update
                    updateThemeIcon(getStoredTheme());
                }
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initToggle);
            } else {
                initToggle();
            }

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
                const currentTheme = getStoredTheme();
                if (currentTheme === 'system') {
                    applyTheme('system');
                }
            });
        })();
    </script>

    {{-- Load compiled/published JS if available, otherwise fallback to Vite dev build --}}
    @if (file_exists(public_path('vendor/laravel-command-center/js/app.js')))
        <script src="{{ asset('vendor/laravel-command-center/js/app.js') }}" defer></script>
    @else
        @vite(['resources/js/app.js'])
    @endif

    @stack('scripts')
</body>

</html>
