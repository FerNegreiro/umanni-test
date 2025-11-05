<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-t">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-dark antialiased">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-6 pt-sm-0 bg-light">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-dark" />
                </a>
            </div>

            <div class="w-100 w-sm-100-md mt-4 px-4 py-4 bg-white shadow-sm overflow-hidden rounded-lg" style="max-width: 450px;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
