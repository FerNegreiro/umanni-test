<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-vh-100 bg-light">
            <div class="container">
                @if (Route::has('login'))
                    <nav class="d-flex justify-content-end p-3">
                        @auth
                            <a
                                href="{{ url('/admin/dashboard') }}"
                                class="btn btn-outline-secondary"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="btn btn-outline-primary me-2"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="btn btn-primary"
                                >
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
            
            <div class="container text-center" style="padding-top: 10rem;">
                <h1>Umanni Fullstack Test</h1>
                <p class="lead text-muted">Project Environment is Running.</p>
            </div>
        </div>
    </body>
</html>
