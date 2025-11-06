<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="rental">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'RENTAL PEES') : config('app.name', 'RENTAL PEES') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-slate-50 to-sky-50 dark:from-slate-900 dark:to-slate-800 text-base-content">
        <!-- Subtle Background Pattern -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -left-24 top-16 h-96 w-96 rounded-full bg-sky-500/5 dark:bg-sky-500/10 blur-3xl"></div>
            <div class="absolute bottom-12 right-8 h-96 w-96 rounded-full bg-blue-500/5 dark:bg-blue-500/10 blur-3xl"></div>
        </div>

        <!-- Theme Toggle -->
        <div class="fixed top-4 right-4 z-50" x-data="{
                theme: (() => { try { return localStorage.getItem('theme') ?? 'rental'; } catch { return 'rental'; } })(),
                init() {
                    document.documentElement.setAttribute('data-theme', this.theme);
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');
                },
                toggle() {
                    this.theme = this.theme === 'rental' ? 'dark' : 'rental';
                    try { localStorage.setItem('theme', this.theme); } catch (error) {}
                    document.documentElement.setAttribute('data-theme', this.theme);
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');
                },
                get icon() {
                    return this.theme === 'dark' ? 'bx bx-sun' : 'bx bx-moon';
                },
                get label() {
                    return this.theme === 'dark' ? 'Light mode' : 'Dark mode';
                }
            }"
            x-init="init()">
            <button type="button" 
                    class="btn btn-sm btn-circle bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border-slate-200 dark:border-slate-700 shadow-lg hover:shadow-xl" 
                    @click="toggle" 
                    :aria-label="label" 
                    :title="label">
                <i :class="icon" class="text-xl text-slate-700 dark:text-slate-300"></i>
            </button>
        </div>

        <div class="relative flex min-h-screen flex-col">
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
