<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="border-b border-base-200 bg-base-100/90 shadow-sm backdrop-blur supports-[backdrop-filter]:bg-base-100/70">
    @php
        $user = auth()->user();
        $navLinks = [
            [
                'label' => 'Dashboard',
                'route' => route('dashboard'),
                'active' => request()->routeIs('dashboard'),
            ],
        ];

        if ($user?->isAdmin()) {
            $navLinks = array_merge($navLinks, [
                [
                    'label' => 'PlayStation',
                    'route' => route('admin.playstation'),
                    'active' => request()->routeIs('admin.playstation'),
                ],
                [
                    'label' => 'Members',
                    'route' => route('admin.members'),
                    'active' => request()->routeIs('admin.members'),
                ],
                [
                    'label' => 'Tarif',
                    'route' => route('admin.tarif'),
                    'active' => request()->routeIs('admin.tarif'),
                ],
                [
                    'label' => 'Laporan',
                    'route' => route('admin.laporan'),
                    'active' => request()->routeIs('admin.laporan'),
                ],
            ]);
        }

        if ($user?->isAdmin() || $user?->isKaryawan()) {
            $navLinks = array_merge($navLinks, [
                [
                    'label' => 'Pelanggan',
                    'route' => route('pelanggan'),
                    'active' => request()->routeIs('pelanggan'),
                ],
                [
                    'label' => 'Transaksi Sewa',
                    'route' => route('transaksi.sewa'),
                    'active' => request()->routeIs('transaksi.sewa'),
                ],
                [
                    'label' => 'Transaksi Kembali',
                    'route' => route('transaksi.kembali'),
                    'active' => request()->routeIs('transaksi.kembali'),
                ],
            ]);
        }
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 text-lg font-semibold tracking-tight">
                    <x-application-logo class="h-11 w-11" />
                    <span class="hidden sm:inline">{{ config('app.name', 'RENTAL PEES') }}</span>
                </a>

                <div class="hidden items-center gap-2 lg:flex">
                    @foreach ($navLinks as $link)
                        <x-nav-link :href="$link['route']" :active="$link['active']" wire:navigate>
                            {{ $link['label'] }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <div class="hidden items-center gap-4 lg:flex">
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
                    class="flex items-center">
                    <button type="button" class="btn btn-sm btn-ghost" @click="toggle" :aria-label="label" title="Ganti tema">
                        <i :class="icon" class="text-lg"></i>
                    </button>
                </div>
                <div class="flex flex-col items-end text-sm">
                    <span class="font-semibold" x-data="{{ json_encode(['name' => $user->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                    <span class="text-base-content/60">{{ $user->email }}</span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="btn btn-sm btn-outline">
                            Menu
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center gap-2 lg:hidden">
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
                    class="flex items-center">
                    <button type="button" class="btn btn-ghost btn-sm" @click="toggle" :aria-label="label" title="Ganti tema">
                        <i :class="icon" class="text-lg"></i>
                    </button>
                </div>
                <button @click="open = ! open" class="btn btn-ghost btn-sm">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="border-t border-base-300 bg-base-100 lg:hidden">
        <div class="space-y-2 px-4 py-4">
            @foreach ($navLinks as $link)
                <x-responsive-nav-link :href="$link['route']" :active="$link['active']" wire:navigate>
                    {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <div class="border-t border-base-300 px-4 py-4">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm font-semibold text-base-content">Tema</span>
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
                    class="flex items-center">
                    <button type="button" class="btn btn-sm btn-ghost" @click="toggle" :aria-label="label" title="Ganti tema">
                        <i :class="icon" class="text-lg"></i>
                    </button>
                </div>
            </div>
            <div class="text-sm">
                <div class="font-semibold" x-data="{{ json_encode(['name' => $user->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-base-content/60">{{ $user->email }}</div>
            </div>

            <div class="mt-3 space-y-2">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
