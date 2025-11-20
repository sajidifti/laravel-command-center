<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Management Login</title>
    {{-- Load compiled/published assets if available (published to public/vendor/laravel-command-center) --}}
    @if (file_exists(public_path('vendor/laravel-command-center/css/app.css')))
        <link rel="stylesheet" href="{{ asset('vendor/laravel-command-center/css/app.css') }}">
    @else
        {{-- Fallback to Vite during package development --}}
        @vite(['resources/css/app.css'])
    @endif
</head>

<body class="bg-linear-to-br from-gray-900 via-gray-800 to-gray-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-gray-800 shadow-2xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-linear-to-r from-red-600 to-red-700 px-8 py-6">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <div>
                        <h2 class="text-2xl font-bold text-white">System Management</h2>
                        <p class="text-red-100 text-sm">Authorized Access Only</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="px-8 py-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (isset($errors) && is_object($errors) && method_exists($errors, 'any') && $errors->any())
                    <div class="mb-4 bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('command-center.login.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                            Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" value="{{ $old['username'] ?? '' }}"
                                required autofocus
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-3 placeholder-gray-400"
                                placeholder="Enter username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-3 placeholder-gray-400"
                                placeholder="Enter password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-linear-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Access System</span>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Warning Footer -->
            <div class="bg-gray-900 px-8 py-4 border-t border-gray-700">
                <div class="flex items-start space-x-2 text-yellow-500">
                    <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-xs">
                        <strong>Warning:</strong> This area is restricted to authorized personnel only.
                        All access attempts are logged and monitored.
                    </p>
                </div>
            </div>
        </div>

        <!-- Back to Site Link -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}"
                class="text-gray-400 hover:text-gray-300 text-sm flex items-center justify-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Website</span>
            </a>
        </div>
    </div>
</body>

</html>
