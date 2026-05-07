<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UniClub Hub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Animated Gradient Background -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-600 via-purple-700 to-pink-500">
            
            <div class="z-10">
                <a href="/" class="flex flex-col items-center gap-2 mb-4 group">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-2xl shadow-xl group-hover:scale-110 transition-transform duration-300">
                        <x-application-logo class="w-12 h-12 fill-current text-white" />
                    </div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">
                        UniClub<span class="text-pink-200">Hub</span>
                    </h1>
                </a>
            </div>

            <!-- Glassmorphism Card -->
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl overflow-hidden sm:rounded-3xl z-10">
                {{ $slot }}
            </div>
            
            <!-- Decorative Blobs -->
            <div class="absolute top-20 left-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-20 right-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>
    </body>
</html>