<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div x-data="{ darkMode: document.documentElement.classList.contains('dark') }" class="absolute right-4 top-4">
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-200 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    aria-label="Toggle dark mode"
                    @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode); localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                >
                    <span aria-hidden="true" x-text="darkMode ? '☀' : '☾'"></span>
                    <span x-text="darkMode ? 'Light mode' : 'Dark mode'"></span>
                </button>
            </div>

            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
