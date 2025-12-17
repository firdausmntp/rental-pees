@php
    $title = 'Landing Page';
    $tarifPrices = $tarifPrices ?? [];
    $pricePs3 = $tarifPrices['PS3'] ?? null;
    $pricePs4 = $tarifPrices['PS4'] ?? null;
    $pricePs5 = $tarifPrices['PS5'] ?? null;
    $formatPrice = function (?float $price, bool $short = false) {
        if ($price === null) {
            return $short ? '-' : 'Rp -';
        }

        if ($short) {
            return rtrim(rtrim(number_format($price / 1000, 1, '.', ''), '0'), '.') . 'K';
        }

        return 'Rp ' . number_format($price, 0, ',', '.');
    };
@endphp

@extends('layouts.guest')

@section('content')
    <!-- Hero Section with Professional Sky Blue -->
    <section class="relative overflow-hidden bg-gradient-to-br from-sky-500 via-blue-500 to-cyan-600 pb-20 pt-24">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 to-transparent"></div>
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-300/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1s"></div>
        </div>

        <div
            class="relative mx-auto flex w-full max-w-6xl flex-col gap-16 px-4 sm:px-6 lg:flex-row lg:items-center lg:gap-20">
            <div class="w-full space-y-8 lg:w-1/2">
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-white/20 backdrop-blur-sm px-6 py-3 text-sm font-semibold text-white border border-white/30">
                    <i class='bx bxs-game text-xl'></i> Platform Rental PS #1 di Indonesia
                </span>

                <h1 class="text-5xl font-extrabold leading-tight text-white sm:text-6xl drop-shadow-2xl">
                    Sewa PlayStation<br />
                    <span class="text-cyan-100">Jadi Lebih Mudah!</span>
                </h1>

                <p class="text-xl text-white/90 drop-shadow-lg">
                    Beli voucher online, bayar cash/QRIS, dan main kapan aja! Sistem voucher digital dengan monitoring
                    real-time.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                        class="btn btn-lg gap-2 bg-white text-sky-600 hover:bg-white/90 border-0 shadow-2xl transform hover:scale-105 transition-all">
                        <i class='bx bx-rocket text-2xl'></i>
                        Daftar Gratis
                    </a>
                    <a href="#pricing"
                        class="btn btn-lg gap-2 bg-white/20 text-white hover:bg-white/30 border-2 border-white backdrop-blur-sm">
                        <i class='bx bx-purchase-tag-alt text-xl'></i>
                        Lihat Harga
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <div class="grid gap-6 sm:grid-cols-2">
                    <!-- PS5 Card -->
                    <article
                        class="card bg-gradient-to-br from-white to-sky-50 shadow-2xl transform hover:scale-105 transition-all border-4 border-white/50">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" viewBox="0 0 24 24" class="text-white">
                                        <path
                                            d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7">
                                        </path>
                                    </svg>
                                </div>
                                <span
                                    class="badge badge-lg bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0">Premium</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-800">PlayStation 5</h3>
                            <p class="text-sm text-slate-600 mt-2">
                                4K HDR, DualSense haptic, loading super-cepat!
                            </p>
                            <div class="mt-4 flex items-end gap-2">
                                <span class="text-5xl font-extrabold text-sky-600">{{ $formatPrice($pricePs5, true) }}</span>
                                <span class="pb-2 text-sm font-medium text-slate-500">per jam</span>
                            </div>
                        </div>
                    </article>

                    <!-- PS4 Card -->
                    <article
                        class="card bg-gradient-to-br from-white to-blue-50 shadow-2xl transform hover:scale-105 transition-all">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" viewBox="0 0 24 24" class="text-white">
                                        <path
                                            d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7">
                                        </path>
                                    </svg>
                                </div>
                                <span
                                    class="badge badge-lg bg-gradient-to-r from-blue-500 to-cyan-600 text-white border-0">Favorit</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-800">PlayStation 4</h3>
                            <p class="text-sm text-slate-600 mt-2">
                                Game lengkap, performa stabil, turnamen ready!
                            </p>
                            <div class="mt-4 flex items-end gap-2">
                                <span class="text-5xl font-extrabold text-blue-600">{{ $formatPrice($pricePs4, true) }}</span>
                                <span class="pb-2 text-sm font-medium text-slate-500">per jam</span>
                            </div>
                        </div>
                    </article>

                    <!-- Voucher Info -->
                    <article
                        class="card bg-gradient-to-br from-cyan-500 to-blue-600 shadow-2xl sm:col-span-2 transform hover:scale-105 transition-all">
                        <div class="card-body flex-row items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center">
                                <i class="bx bxs-coupon text-4xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white">Sistem Voucher Digital</h3>
                                <p class="text-white/90 text-sm mt-1">Beli online, approve kasir, redeem & main! +5 menit
                                    toleransi setup.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section id="features" class="bg-gradient-to-br from-slate-50 to-sky-50 dark:from-slate-900 dark:to-slate-800 py-20">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6">
            <div class="mb-16 text-center">
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-sky-100 dark:bg-sky-900/50 backdrop-blur-sm px-6 py-3 text-sm font-semibold text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800 mb-4">
                    <i class='bx bxs-bolt-circle text-xl'></i> Kenapa {{ config('app.name', 'LOS SEKOLITOS') }}?
                </span>
                <h2 class="text-4xl font-extrabold text-slate-800 dark:text-slate-100 sm:text-5xl">
                    Fitur Terbaik untuk Pengalaman Main yang <span class="text-sky-600 dark:text-sky-400">Maksimal</span>
                </h2>
                <p class="mx-auto mt-6 max-w-2xl text-xl text-slate-600 dark:text-slate-400">
                    Semua yang kamu butuhkan untuk sewa PS dengan mudah, cepat, dan aman.
                </p>
            </div>

            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Voucher Digital -->
                <article
                    class="card bg-white dark:bg-slate-800 shadow-xl transform hover:-translate-y-2 transition-all border border-slate-100 dark:border-slate-700">
                    <div class="card-body">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-xl mb-4">
                            <i class='bx bxs-coupon text-4xl text-white'></i>
                        </div>
                        <h3 class="card-title text-2xl text-slate-800 dark:text-slate-100">Voucher Digital</h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            Beli voucher online kapan aja! Pilih durasi, bayar cash/QRIS, tunggu approve, lalu redeem &
                            main.
                        </p>
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <span
                                class="badge badge-lg bg-sky-100 text-sky-800 dark:bg-sky-900/50 dark:text-sky-300 border-0">Praktis</span>
                            <span
                                class="badge badge-lg bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-0">Cashless</span>
                        </div>
                    </div>
                </article>

                <!-- Live Monitoring -->
                <article
                    class="card bg-white dark:bg-slate-800 shadow-xl transform hover:-translate-y-2 transition-all border border-slate-100 dark:border-slate-700">
                    <div class="card-body">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-xl mb-4">
                            <i class='bx bxs-dashboard text-4xl text-white'></i>
                        </div>
                        <h3 class="card-title text-2xl text-slate-800 dark:text-slate-100">Live Monitoring</h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            Pantau real-time semua PS dengan countdown timer, progress bar, dan alert overtime otomatis!
                        </p>
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <span
                                class="badge badge-lg bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300 border-0">Real-time</span>
                            <span
                                class="badge badge-lg bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-0">Auto-refresh</span>
                        </div>
                    </div>
                </article>

                <!-- +5 Menit Toleransi -->
                <article
                    class="card bg-white dark:bg-slate-800 shadow-xl transform hover:-translate-y-2 transition-all border border-slate-100 dark:border-slate-700">
                    <div class="card-body">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-xl mb-4">
                            <i class='bx bxs-time-five text-4xl text-white'></i>
                        </div>
                        <h3 class="card-title text-2xl text-slate-800 dark:text-slate-100">+5 Menit Setup</h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            Waktu ekstra gratis untuk setup game & controller. Beli 1 jam = main 1 jam 5 menit!
                        </p>
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <span
                                class="badge badge-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border-0">Bonus</span>
                            <span
                                class="badge badge-lg bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 border-0">Fair</span>
                        </div>
                    </div>
                </article>

                <!-- Approval Workflow -->
                <article
                    class="card bg-white dark:bg-slate-800 shadow-xl transform hover:-translate-y-2 transition-all border border-slate-100 dark:border-slate-700">
                    <div class="card-body">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-500 flex items-center justify-center shadow-xl mb-4">
                            <i class='bx bxs-check-shield text-4xl text-white'></i>
                        </div>
                        <h3 class="card-title text-2xl text-slate-800 dark:text-slate-100">Approval Cepat</h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            Kasir approve pembayaran cash dalam hitungan detik. Transaksi aman & tercatat otomatis.
                        </p>
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <span
                                class="badge badge-lg bg-sky-100 text-sky-800 dark:bg-sky-900/50 dark:text-sky-300 border-0">Aman</span>
                            <span
                                class="badge badge-lg bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-0">Cepat</span>
                        </div>
                    </div>
                </article>

                <!-- Multi-Payment -->
                <article
                    class="card bg-white dark:bg-slate-800 shadow-xl transform hover:-translate-y-2 transition-all border border-slate-100 dark:border-slate-700">
                    <div class="card-body">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-xl mb-4">
                            <i class='bx bxs-credit-card text-4xl text-white'></i>
                        </div>
                        <h3 class="card-title text-2xl text-slate-800 dark:text-slate-100">Multi Payment</h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            Bayar pakai cash atau QRIS. Fleksibel sesuai kebutuhanmu, semua metode diterima!
                        </p>
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <span
                                class="badge badge-lg bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-0">Cash</span>
                            <span
                                class="badge badge-lg bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 border-0">QRIS</span>
                        </div>
                    </div>
                </article>

                <!-- Konsol Terawat -->
                <article
                    class="card bg-white dark:bg-slate-800 shadow-xl transform hover:-translate-y-2 transition-all border border-slate-100 dark:border-slate-700">
                    <div class="card-body">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-xl mb-4">
                            <i class='bx bxs-like text-4xl text-white'></i>
                        </div>
                        <h3 class="card-title text-2xl text-slate-800 dark:text-slate-100">Konsol Terawat</h3>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            Semua unit dicek rutin, controller ori, game update terbaru. Performa selalu maksimal!
                        </p>
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <span
                                class="badge badge-lg bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300 border-0">Berkualitas</span>
                            <span
                                class="badge badge-lg bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-0">Original</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- PlayStation Units Available -->
    <section class="bg-white dark:bg-slate-900 py-20">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6">
            <div class="mb-16 text-center">
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-sky-100 dark:bg-sky-900/50 backdrop-blur-sm px-6 py-3 text-sm font-semibold text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800 mb-4">
                    <i class='bx bxs-devices text-xl'></i> Unit PlayStation Kami
                </span>
                <h2 class="text-4xl font-extrabold text-slate-800 dark:text-slate-100 sm:text-5xl">
                    <span class="text-sky-600 dark:text-sky-400">7 Unit</span> Siap Pakai
                </h2>
                <p class="mx-auto mt-6 max-w-2xl text-xl text-slate-600 dark:text-slate-400">
                    Semua konsol dirawat rutin dan siap memberikan pengalaman gaming terbaik
                </p>
            </div>

            <!-- PS3 Units -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24" class="text-white">
                            <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                        </svg>
                    </div>
                    PlayStation 3 <span class="text-slate-500 dark:text-slate-400 text-lg">(2 Unit)</span>
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="card bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-slate-200 dark:border-slate-600">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-slate-700 text-white border-0">PS3-001</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 3 Slim #1</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">Classic games library lengkap</p>
                        </div>
                    </div>
                    <div class="card bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-slate-200 dark:border-slate-600">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-slate-700 text-white border-0">PS3-002</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 3 Slim #2</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">Party game specialist</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PS4 Units -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24" class="text-white">
                            <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                        </svg>
                    </div>
                    PlayStation 4 <span class="text-slate-500 dark:text-slate-400 text-lg">(3 Unit)</span>
                </h3>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="card bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-blue-200 dark:border-blue-800">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-blue-600 text-white border-0">PS4-001</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 4 Pro #1</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">4K ready, premium performance</p>
                        </div>
                    </div>
                    <div class="card bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-blue-200 dark:border-blue-800">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-blue-600 text-white border-0">PS4-002</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 4 Slim #2</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">Compact & energy efficient</p>
                        </div>
                    </div>
                    <div class="card bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-blue-200 dark:border-blue-800">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-blue-600 text-white border-0">PS4-003</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 4 #3</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">Original model, reliable</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PS5 Units -->
            <div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24" class="text-white">
                            <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                        </svg>
                    </div>
                    PlayStation 5 <span class="text-slate-500 dark:text-slate-400 text-lg">(2 Unit)</span>
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="card bg-gradient-to-br from-sky-50 to-blue-50 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-sky-300 dark:border-sky-700">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0">PS5-001</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 5 Digital #1</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">Full digital, sleek design</p>
                        </div>
                    </div>
                    <div class="card bg-gradient-to-br from-sky-50 to-blue-50 dark:from-slate-800 dark:to-slate-700 shadow-lg border-2 border-sky-300 dark:border-sky-700">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-lg bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0">PS5-002</span>
                                <span class="badge badge-lg badge-success gap-1"><i class='bx bxs-circle text-xs'></i>Tersedia</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">PlayStation 5 Disc #2</h4>
                            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">Supports physical games</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('register') }}" class="btn btn-lg btn-primary gap-2">
                    <i class='bx bx-game text-2xl'></i>
                    Mulai Main Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="bg-gradient-to-br from-sky-500 via-blue-600 to-cyan-600 py-20">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6">
            <div class="mb-16 text-center">
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-white/20 backdrop-blur-sm px-6 py-3 text-sm font-semibold text-white border border-white/30 mb-4">
                    <i class='bx bxs-purchase-tag-alt text-xl'></i> Paket Terbaik
                </span>
                <h2 class="text-4xl font-extrabold text-white sm:text-5xl drop-shadow-2xl">
                    Harga Fleksibel & <span class="text-cyan-100">Terjangkau</span>
                </h2>
                <p class="mx-auto mt-6 max-w-2xl text-xl text-white/90">
                    Pilih konsol favoritmu dengan harga yang ramah di kantong!
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <!-- PS3 Card -->
                <div class="card bg-white dark:bg-slate-800 shadow-2xl transform hover:-translate-y-2 transition-all">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" viewBox="0 0 24 24" class="text-white">
                                        <path
                                            d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7">
                                        </path>
                                    </svg>
                            </div>
                            <span
                                class="badge badge-lg bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 border-0">Hemat</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">PlayStation 3</h3>
                        <p class="text-sm text-slate-600 mt-2">
                            Nostalgia ke era klasik, party game & arcade legend!
                        </p>
                        <div class="mt-6 flex items-end gap-2">
                            <span class="text-5xl font-extrabold text-slate-800 dark:text-slate-100">{{ $formatPrice($pricePs3) }}</span>
                            <span class="pb-2 text-sm font-medium text-slate-500 dark:text-slate-400">per jam</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-sm text-slate-700 dark:text-slate-300">
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-emerald-500"></i>
                                <span>Game klasik: PES, GTA, Tekken</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-emerald-500"></i>
                                <span>2 unit tersedia (PS3-001, PS3-002)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-emerald-500"></i>
                                <span>Tanpa denda keterlambatan</span>
                            </li>
                        </ul>
                        <a href="{{ route('login') }}" class="btn btn-outline btn-primary mt-8 w-full">Sewa PS3</a>
                    </div>
                </div>

                <!-- PS4 Card - Featured -->
                <div
                    class="card bg-gradient-to-br from-sky-500 to-blue-600 shadow-2xl transform hover:-translate-y-3 transition-all scale-105 border-4 border-cyan-300">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" viewBox="0 0 24 24" class="text-white">
                                        <path
                                            d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7">
                                        </path>
                                    </svg>
                            </div>
                            <span class="badge badge-lg bg-white text-sky-700 border-0 font-bold">🔥 Best</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white">PlayStation 4</h3>
                        <p class="text-sm text-white/90 mt-2">
                            Library lengkap, grafis tajam, event mingguan!
                        </p>
                        <div class="mt-6 flex items-end gap-2">
                            <span class="text-5xl font-extrabold text-white">{{ $formatPrice($pricePs4) }}</span>
                            <span class="pb-2 text-sm font-medium text-white/70">per jam</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-sm text-white">
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-cyan-200"></i>
                                <span>FIFA, eFootball, fighting game update!</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-cyan-200"></i>
                                <span>3 unit tersedia (Pro, Slim, Standard)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-cyan-200"></i>
                                <span>Tanpa denda keterlambatan</span>
                            </li>
                        </ul>
                        <a href="{{ route('login') }}"
                            class="btn bg-white text-sky-600 hover:bg-white/90 border-0 mt-8 w-full shadow-xl">Sewa PS4</a>
                    </div>
                </div>

                <!-- PS5 Card -->
                <div class="card bg-white dark:bg-slate-800 shadow-2xl transform hover:-translate-y-2 transition-all">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-600 to-blue-700 flex items-center justify-center shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" viewBox="0 0 24 24" class="text-white">
                                        <path
                                            d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7">
                                        </path>
                                    </svg>
                            </div>
                            <span
                                class="badge badge-lg bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0">Premium</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100">PlayStation 5</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                            4K HDR, DualSense haptic, loading super-cepat!
                        </p>
                        <div class="mt-6 flex items-end gap-2">
                            <span class="text-5xl font-extrabold text-slate-800 dark:text-slate-100">{{ $formatPrice($pricePs5) }}</span>
                            <span class="pb-2 text-sm font-medium text-slate-500 dark:text-slate-400">per jam</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-sm text-slate-700 dark:text-slate-300">
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-sky-500"></i>
                                <span>Eksklusif next-gen games only</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-sky-500"></i>
                                <span>2 unit tersedia (Digital & Disc Edition)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bx bxs-check-circle text-lg text-sky-500"></i>
                                <span>Tanpa denda keterlambatan</span>
                            </li>
                        </ul>
                        <a href="{{ route('login') }}" class="btn btn-outline btn-primary mt-8 w-full">Sewa PS5</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-br from-blue-600 via-sky-600 to-cyan-600 py-20">
        <div class="relative mx-auto w-full max-w-6xl px-4 sm:px-6 text-center">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-cyan-300/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1.5s"></div>

            <div class="relative">
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-white/20 backdrop-blur-sm px-6 py-3 text-sm font-semibold text-white border border-white/30 mb-6">
                    <i class='bx bxs-rocket text-xl'></i> Yuk Mulai Sekarang!
                </div>

                <h2 class="text-5xl font-extrabold text-white sm:text-6xl drop-shadow-2xl mb-6">
                    Siap Main <span class="text-cyan-100">Sekarang?</span>
                </h2>

                <p class="mx-auto max-w-2xl text-xl text-white/90 mb-10 drop-shadow-lg">
                    Daftar gratis, beli voucher online, dan rasakan kemudahan rental PlayStation dengan sistem modern!
                </p>

                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <a href="{{ route('register') }}"
                        class="btn btn-lg gap-2 bg-white text-sky-600 hover:bg-white/90 border-0 shadow-2xl transform hover:scale-105 transition-all">
                        <i class='bx bxs-user-plus text-2xl'></i>
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}"
                        class="btn btn-lg gap-2 bg-white/20 text-white hover:bg-white/30 border-2 border-white backdrop-blur-sm">
                        <i class='bx bxs-log-in text-xl'></i>
                        Login Sekarang
                    </a>
                </div>

                <!-- Info Cards -->
                <div class="grid gap-6 sm:grid-cols-2 max-w-4xl mx-auto">
                    <div class="card bg-white/10 backdrop-blur-lg shadow-xl border-2 border-white/20">
                        <div class="card-body">
                            <div
                                class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-3">
                                <i class='bx bxs-time-five text-3xl text-white'></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Jam Operasional</h3>
                            <ul class="mt-4 space-y-2 text-sm text-white/90">
                                <li><span class="font-semibold">Senin - Kamis:</span> 10.00 - 22.00</li>
                                <li><span class="font-semibold">Jumat:</span> 13.00 - 23.00</li>
                                <li><span class="font-semibold">Sabtu - Minggu:</span> 09.00 - 23.00</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card bg-white/10 backdrop-blur-lg shadow-xl border-2 border-white/20">
                        <div class="card-body">
                            <div
                                class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-3">
                                <i class='bx bxs-book-bookmark text-3xl text-white'></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Cara Booking</h3>
                            <ul class="mt-4 space-y-2 text-sm text-white/90">
                                <li><span class="font-semibold">1.</span> Daftar/Login akun</li>
                                <li><span class="font-semibold">2.</span> Beli voucher online</li>
                                <li><span class="font-semibold">3.</span> Approve & redeem</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
