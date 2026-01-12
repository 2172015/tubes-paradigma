<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-900 via-emerald-950 to-gray-900">
            
            <div class="mb-8 text-center">
                <a href="/" class="d-flex justify-content-center">
                    <img src="{{ asset('dist/img/logo-pixelate.png') }}" alt="PIXELATE Logo" style="width: 150px; height: auto; margin: auto;" class="drop-shadow-2xl rounded-3xl">
                </a>
                <p class="text-emerald-200/70 text-sm mt-4 tracking-widest uppercase font-semibold">
                    AI-Powered Design Assets
                </p>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 px-6 py-8 bg-gray-800 shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-700">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-gray-500 text-xs">
                &copy; {{ date('Y') }} Online POS Project.
            </div>
        </div>
    </body>
</html>