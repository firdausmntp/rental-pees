<!DOCTYPE html>
<html lang="en" data-theme="rental">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'LOS SEKOLITOS') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

        <script>
            (function () {
                try {
                    const storedTheme = localStorage.getItem('theme');
                    if (storedTheme) {
                        document.documentElement.setAttribute('data-theme', storedTheme);
                        document.documentElement.classList.toggle('dark', storedTheme === 'dark');
                    }
                } catch (error) {
                    console.warn('Theme preference unavailable', error);
                }
            })();
        </script>
    </head>
    <body class="bg-base-200 text-base-content">
        <div class="mx-auto w-full max-w-5xl px-4 py-10">
            @yield('content')
        </div>

        @livewireScripts
    </body>
</html>
