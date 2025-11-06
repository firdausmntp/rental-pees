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

        <style>[x-cloak]{display:none!important;}</style>

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
    <body class="font-sans antialiased bg-gradient-to-br from-base-200 via-base-100 to-base-200 text-base-content">
        @php
            $user = auth()->user();
            $navLinks = [];

            // Owner Navigation - Full Access
            if ($user?->isOwner()) {
                $navLinks = [
                    [
                        'label' => 'Dashboard',
                        'route' => route('owner.dashboard'),
                        'active' => request()->routeIs('owner.dashboard'),
                        'icon' => 'bx bxs-dashboard',
                    ],
                    [
                        'label' => 'PlayStation',
                        'route' => route('owner.playstation'),
                        'active' => request()->routeIs('owner.playstation'),
                        'icon' => 'bx bx-joystick-alt',
                    ],
                    [
                        'label' => 'User Management',
                        'route' => route('owner.users'),
                        'active' => request()->routeIs('owner.users'),
                        'icon' => 'bx bx-id-card',
                    ],
                    [
                        'label' => 'Voucher',
                        'route' => route('owner.voucher'),
                        'active' => request()->routeIs('owner.voucher'),
                        'icon' => 'bx bxs-coupon',
                    ],
                    [
                        'label' => 'Laporan',
                        'route' => route('owner.laporan'),
                        'active' => request()->routeIs('owner.laporan'),
                        'icon' => 'bx bx-bar-chart-alt-2',
                    ],
                    [
                        'label' => 'Redeem Voucher',
                        'route' => route('owner.voucher.redeem'),
                        'active' => request()->routeIs('owner.voucher.redeem'),
                        'icon' => 'bx bx-qr-scan',
                    ],
                ];
            }
            // Karyawan Navigation - Limited Access
            elseif ($user?->isKaryawan()) {
                $navLinks = [
                    [
                        'label' => 'Dashboard',
                        'route' => route('karyawan.dashboard'),
                        'active' => request()->routeIs('karyawan.dashboard'),
                        'icon' => 'bx bxs-dashboard',
                    ],
                    [
                        'label' => 'User Management',
                        'route' => route('karyawan.users'),
                        'active' => request()->routeIs('karyawan.users'),
                        'icon' => 'bx bx-id-card',
                    ],
                    [
                        'label' => 'Voucher Management',
                        'route' => route('karyawan.voucher'),
                        'active' => request()->routeIs('karyawan.voucher'),
                        'icon' => 'bx bxs-coupon',
                    ],
                    [
                        'label' => 'Redeem Voucher',
                        'route' => route('karyawan.voucher.redeem'),
                        'active' => request()->routeIs('karyawan.voucher.redeem'),
                        'icon' => 'bx bx-qr-scan',
                    ],
                ];
            }
            // Member Navigation
            elseif ($user?->isMember()) {
                $navLinks = [
                    [
                        'label' => 'Dashboard',
                        'route' => route('member.dashboard'),
                        'active' => request()->routeIs('member.dashboard'),
                        'icon' => 'bx bxs-dashboard',
                    ],
                    [
                        'label' => 'Jadwal PS',
                        'route' => route('member.jadwal'),
                        'active' => request()->routeIs('member.jadwal'),
                        'icon' => 'bx bx-calendar-check',
                    ],
                    [
                        'label' => 'Beli Voucher',
                        'route' => route('member.beli'),
                        'active' => request()->routeIs('member.beli'),
                        'icon' => 'bx bx-shopping-bag',
                    ],
                ];
            }
            // Default fallback
            else {
                $navLinks = [
                    [
                        'label' => 'Dashboard',
                        'route' => route('dashboard'),
                        'active' => request()->routeIs('dashboard'),
                        'icon' => 'bx bxs-dashboard',
                    ],
                ];
            }
        @endphp

        <div x-data="{ 
            sidebarOpen: false, 
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            toggleCollapse() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            }
        }" class="flex min-h-screen">
            <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-base-content/80 backdrop-blur-sm transition-opacity lg:hidden" @click="sidebarOpen = false"></div>

            <aside
                class="fixed inset-y-0 left-0 z-50 transform border-r border-base-200 bg-base-100 shadow-2xl transition-all duration-300 ease-in-out lg:static lg:flex lg:flex-col lg:shadow-none"
                :class="{ 
                    '-translate-x-full lg:translate-x-0': !sidebarOpen, 
                    'translate-x-0': sidebarOpen,
                    'w-72': !sidebarCollapsed,
                    'lg:w-20': sidebarCollapsed 
                }"
            >
                <div class="flex items-center gap-3 px-6 py-6">
                    <x-application-logo class="flex-shrink-0" />
                    <div x-show="!sidebarCollapsed" x-transition class="min-w-0">
                        <p class="text-sm font-semibold uppercase tracking-wide text-primary/80 truncate">{{ config('app.name', 'RENTAL PEES') }}</p>
                        <p class="text-xs text-base-content/60 truncate">Panel pengelolaan rental</p>
                    </div>
                    <!-- Toggle Button Desktop -->
                    <button 
                        @click="toggleCollapse()" 
                        class="ml-auto btn btn-xs btn-ghost btn-circle hidden lg:flex flex-shrink-0"
                        :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                    >
                        <i class="bx text-lg" :class="sidebarCollapsed ? 'bx-chevron-right' : 'bx-chevron-left'"></i>
                    </button>
                </div>

                <div class="px-3">
                    <nav class="space-y-1">
                        @foreach ($navLinks as $link)
                            <a
                                href="{{ $link['route'] }}"
                                wire:navigate
                                @class([
                                    'group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                                    'bg-primary/15 text-primary shadow-sm' => $link['active'],
                                    'text-base-content/70 hover:bg-base-200/80 hover:text-base-content' => ! $link['active'],
                                ])
                                :title="sidebarCollapsed ? '{{ $link['label'] }}' : ''"
                                :class="sidebarCollapsed ? 'justify-center' : ''"
                            >
                                <i class="{{ $link['icon'] }} text-lg"></i>
                                <span x-show="!sidebarCollapsed" x-transition>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="sticky top-0 z-30 border-b border-base-200 bg-base-100/85 backdrop-blur supports-[backdrop-filter]:bg-base-100/70">
                    <div class="flex items-center justify-between px-4 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <button type="button" class="btn btn-sm btn-ghost lg:hidden" @click="sidebarOpen = true">
                                <i class="bx bx-menu text-xl"></i>
                            </button>
                            <div class="hidden text-sm font-medium text-base-content/70 sm:block">
                                {{ now()->translatedFormat('l, d F Y') }}
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div x-data="{
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
                                        return this.theme === 'dark' ? 'Aktifkan tema terang' : 'Aktifkan tema gelap';
                                    }
                                }"
                                x-init="init()"
                                class="flex items-center"
                            >
                                <button type="button" class="btn btn-sm btn-ghost" @click="toggle" :aria-label="label" title="Ganti tema">
                                    <i :class="icon" class="text-lg"></i>
                                </button>
                            </div>

                            <!-- User Dropdown -->
                            <div class="dropdown dropdown-end">
                                <button tabindex="0" class="btn btn-ghost btn-sm gap-2">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary/15 text-primary rounded-full w-8">
                                            <i class="bx bxs-user text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="hidden sm:flex sm:flex-col sm:items-start">
                                        <span class="text-xs font-semibold">{{ $user->name }}</span>
                                        <span class="text-xs text-base-content/60">{{ ucfirst($user->role ?? 'user') }}</span>
                                    </div>
                                    <i class="bx bx-chevron-down hidden sm:block"></i>
                                </button>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-xl bg-base-100 rounded-box w-64 border border-base-200 mt-3">
                                    <!-- Mobile: Show user info -->
                                    <li class="menu-title sm:hidden">
                                        <div class="flex items-center gap-2 px-2 py-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary/15 text-primary rounded-full w-10">
                                                    <i class="bx bxs-user text-xl"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-base-content">{{ $user->name }}</p>
                                                <p class="text-xs text-base-content/60">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="sm:hidden"><div class="divider my-0"></div></li>
                                    
                                    <li>
                                        <a href="{{ $user->isMember() ? route('member.profile') : ($user->isOwner() ? route('owner.profile') : ($user->isKaryawan() ? route('karyawan.profile') : route('profile'))) }}" wire:navigate class="gap-3">
                                            <i class="bx bx-user-circle text-lg"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li><div class="divider my-0"></div></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full text-left text-error hover:bg-error/10 rounded-lg px-4 py-2 transition-colors">
                                                <i class="bx bx-log-out text-lg"></i>
                                                <span>Keluar</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-8 sm:px-6 lg:px-10">
                    <div class="mx-auto w-full max-w-7xl space-y-6">
                        @if (isset($header))
                            <div class="rounded-2xl border border-base-200 bg-base-100/80 p-5 shadow-sm">
                                {{ $header }}
                            </div>
                        @endif

                        <div class="space-y-6">
                            {{ $slot }}
                        </div>
                    </div>
                </main>

                <footer class="border-t border-base-200 bg-base-100/90">
                    <div class="mx-auto flex w-full max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-sm text-base-content/70 sm:flex-row sm:px-6 lg:px-10">
                        <p>&copy; {{ now()->year }} {{ config('app.name', 'RENTAL PEES') }}. Semua hak dilindungi.</p>
                        <div class="flex items-center gap-4">
                            <span class="text-base-content/50">Support</span>
                            <a href="mailto:rental@example.com" class="link link-hover">rental@example.com</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Toast Notification Container -->
        <div 
            x-data="{ 
                notifications: [],
                show(message, type = 'success') {
                    const id = Date.now();
                    this.notifications.push({ id, message, type });
                    setTimeout(() => {
                        this.remove(id);
                    }, 5000);
                },
                remove(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }
            }"
            @notify.window="show($event.detail.message, $event.detail.type || 'success')"
            class="toast toast-top toast-end z-50"
        >
            <template x-for="notification in notifications" :key="notification.id">
                <div 
                    x-show="true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-8"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-8"
                    class="alert shadow-lg max-w-sm"
                    :class="{
                        'alert-success': notification.type === 'success',
                        'alert-error': notification.type === 'error',
                        'alert-warning': notification.type === 'warning',
                        'alert-info': notification.type === 'info'
                    }"
                >
                    <div class="flex items-center gap-3">
                        <i class="text-2xl" :class="{
                            'bx bxs-check-circle': notification.type === 'success',
                            'bx bxs-error': notification.type === 'error',
                            'bx bxs-error-circle': notification.type === 'warning',
                            'bx bxs-info-circle': notification.type === 'info'
                        }"></i>
                        <span x-text="notification.message"></span>
                    </div>
                    <button @click="remove(notification.id)" class="btn btn-sm btn-ghost btn-circle">
                        <i class="bx bx-x text-xl"></i>
                    </button>
                </div>
            </template>
        </div>

        @stack('modals')
        @livewireScripts
        @stack('scripts')
    </body>
</html>
