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
    <body class="font-sans antialiased bg-base-100 text-base-content">
        <div class="flex min-h-screen flex-col">
            <!-- Modern Navbar dengan Professional Sky Blue -->
            <header class="sticky top-0 z-50 backdrop-blur-lg bg-white/90 dark:bg-slate-900/90 border-b border-sky-100 dark:border-sky-900 shadow-lg">
                <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    
                        <div class="flex flex-col">
                            <span class="text-xl font-bold bg-gradient-to-r from-sky-600 to-blue-600 bg-clip-text text-transparent">{{ config('app.name', 'RENTAL PEES') }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">PlayStation Rental</span>
                        </div>
                    </a>

                    <!-- Desktop Menu -->
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="#features" class="text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">Fitur</a>
                        <a href="#pricing" class="text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">Harga</a>
                        
                        <!-- Theme Toggle -->
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
                            <button type="button" class="btn btn-sm btn-ghost btn-circle" @click="toggle" :aria-label="label" :title="label">
                                <i :class="icon" class="text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="w-px h-6 bg-slate-300 dark:bg-slate-700"></div>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-sm gap-2 bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0 hover:scale-105 transition-transform">
                                <i class='bx bxs-dashboard text-lg'></i>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-ghost gap-2 text-slate-700 dark:text-slate-300">
                                <i class='bx bxs-log-in text-lg'></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm gap-2 bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0 hover:scale-105 transition-transform">
                                <i class='bx bxs-user-plus text-lg'></i>
                                Daftar
                            </a>
                        @endauth
                    </nav>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center gap-2">
                        <!-- Theme Toggle Mobile -->
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
                            x-init="init()">
                            <button type="button" class="btn btn-sm btn-ghost btn-circle" @click="toggle" :aria-label="label" :title="label">
                                <i :class="icon" class="text-xl"></i>
                            </button>
                        </div>
                        
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="btn btn-sm btn-ghost">
                                <i class='bx bx-menu text-2xl'></i>
                            </button>
                            
                            <!-- Mobile Dropdown -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-4 top-20 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 py-2 z-50">
                                <a href="#features" class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors">Fitur</a>
                                <a href="#pricing" class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors">Harga</a>
                                <div class="border-t border-slate-200 dark:border-slate-700 my-2"></div>
                                @auth
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-sky-600 dark:text-sky-400 font-medium hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors">
                                        <i class='bx bxs-dashboard mr-2'></i>Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors">
                                        <i class='bx bxs-log-in mr-2'></i>Login
                                    </a>
                                    <a href="{{ route('register') }}" class="block px-4 py-3 text-sm text-sky-600 dark:text-sky-400 font-medium hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors">
                                        <i class='bx bxs-user-plus mr-2'></i>Daftar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Modern Footer with Professional Colors -->
            <footer class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
                <div class="mx-auto w-full max-w-6xl px-4 py-12 sm:px-6">
                    <div class="grid gap-8 md:grid-cols-4">
                        <!-- Brand Section -->
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-3 mb-4">
                                
                                <div class="flex flex-col">
                                    <span class="text-xl font-bold">{{ config('app.name', 'LOS SEKOLITOS') }}</span>
                                    <span class="text-xs text-sky-400">{{ config('app.name', 'LOS SEKOLITOS') }}</span>
                                </div>
                            </div>
                            <p class="text-sm text-slate-300 mb-4 max-w-md">
                                Rental PlayStation terbaik dengan sistem voucher digital, live monitoring, dan multi payment. Main jadi lebih mudah!
                            </p>
                            <div class="flex gap-3">
                                <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center hover:bg-sky-500/30 transition-colors">
                                    <i class='bx bxl-facebook text-xl'></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center hover:bg-sky-500/30 transition-colors">
                                    <i class='bx bxl-instagram text-xl'></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center hover:bg-sky-500/30 transition-colors">
                                    <i class='bx bxl-whatsapp text-xl'></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center hover:bg-sky-500/30 transition-colors">
                                    <i class='bx bxl-tiktok text-xl'></i>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h3 class="text-sm font-bold mb-4 text-sky-400">Quick Links</h3>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#features" class="text-slate-300 hover:text-sky-400 transition-colors">Fitur</a></li>
                                <li><a href="#pricing" class="text-slate-300 hover:text-sky-400 transition-colors">Harga</a></li>
                                <li><a href="{{ route('login') }}" class="text-slate-300 hover:text-sky-400 transition-colors">Login</a></li>
                                <li><a href="{{ route('register') }}" class="text-slate-300 hover:text-sky-400 transition-colors">Daftar</a></li>
                            </ul>
                        </div>

                        <!-- Contact Info -->
                        <div>
                            <h3 class="text-sm font-bold mb-4 text-sky-400">Hubungi Kami</h3>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li class="flex items-start gap-2">
                                    <i class='bx bxs-phone text-lg text-sky-400'></i>
                                    <span>+62 812-3456-7890</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class='bx bxs-envelope text-lg text-sky-400'></i>
                                    <span>info@rentalpees.com</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class='bx bxs-map text-lg text-sky-400'></i>
                                    <span>Jl. Gaming Center No. 123<br>Jakarta Selatan</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="border-t border-white/10 mt-8 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-400">
                        <span>&copy; {{ now()->year }} {{ config('app.name', 'RENTAL PEES') }}. All rights reserved.</span>
                        <div class="flex items-center gap-4">
                            <button onclick="privacyModal.showModal()" class="hover:text-sky-400 transition-colors">Privacy Policy</button>
                            <span class="text-slate-600">|</span>
                            <button onclick="termsModal.showModal()" class="hover:text-sky-400 transition-colors">Terms of Service</button>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Privacy Policy Modal -->
        <dialog id="privacyModal" class="modal">
            <div class="modal-box max-w-4xl bg-white dark:bg-slate-800">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-2xl mb-4 text-slate-800 dark:text-slate-100">Privacy Policy</h3>
                <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
                    <p class="mb-4"><strong>Terakhir diperbarui:</strong> {{ now()->format('d F Y') }}</p>
                    
                    <h4 class="text-lg font-semibold mt-6 mb-2">1. Informasi yang Kami Kumpulkan</h4>
                    <p class="mb-4">Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, termasuk:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Nama lengkap dan informasi kontak</li>
                        <li>Alamat email dan nomor telepon</li>
                        <li>Informasi pembayaran dan transaksi</li>
                        <li>Data penggunaan layanan rental PlayStation</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">2. Penggunaan Informasi</h4>
                    <p class="mb-4">Informasi yang kami kumpulkan digunakan untuk:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Memproses pesanan dan transaksi rental</li>
                        <li>Mengelola akun member dan voucher digital</li>
                        <li>Memberikan dukungan pelanggan yang lebih baik</li>
                        <li>Mengirimkan notifikasi terkait layanan</li>
                        <li>Meningkatkan kualitas layanan kami</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">3. Perlindungan Data</h4>
                    <p class="mb-4">Kami menerapkan langkah-langkah keamanan untuk melindungi informasi pribadi Anda dari akses tidak sah, perubahan, atau pengungkapan.</p>

                    <h4 class="text-lg font-semibold mt-6 mb-2">4. Pembagian Informasi</h4>
                    <p class="mb-4">Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p>

                    <h4 class="text-lg font-semibold mt-6 mb-2">5. Cookies</h4>
                    <p class="mb-4">Website kami menggunakan cookies untuk meningkatkan pengalaman pengguna dan menganalisis traffic website.</p>

                    <h4 class="text-lg font-semibold mt-6 mb-2">6. Hak Anda</h4>
                    <p class="mb-4">Anda memiliki hak untuk:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Mengakses informasi pribadi Anda</li>
                        <li>Memperbarui atau mengoreksi data Anda</li>
                        <li>Menghapus akun dan data Anda</li>
                        <li>Menarik persetujuan penggunaan data</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">7. Hubungi Kami</h4>
                    <p class="mb-4">Jika Anda memiliki pertanyaan tentang Privacy Policy ini, silakan hubungi kami di:</p>
                    <p class="mb-2"><strong>Email:</strong> privacy@rentalpees.com</p>
                    <p class="mb-2"><strong>Telepon:</strong> +62 812-3456-7890</p>
                </div>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0 hover:from-sky-600 hover:to-blue-700">Tutup</button>
                    </form>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <!-- Terms of Service Modal -->
        <dialog id="termsModal" class="modal">
            <div class="modal-box max-w-4xl bg-white dark:bg-slate-800">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-2xl mb-4 text-slate-800 dark:text-slate-100">Terms of Service</h3>
                <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
                    <p class="mb-4"><strong>Terakhir diperbarui:</strong> {{ now()->format('d F Y') }}</p>
                    
                    <h4 class="text-lg font-semibold mt-6 mb-2">1. Penerimaan Syarat</h4>
                    <p class="mb-4">Dengan mengakses dan menggunakan layanan {{ config('app.name', 'LOS SEKOLITOS') }}, Anda menyetujui untuk terikat dengan syarat dan ketentuan ini.</p>

                    <h4 class="text-lg font-semibold mt-6 mb-2">2. Layanan Rental PlayStation</h4>
                    <p class="mb-4">{{ config('app.name', 'LOS SEKOLITOS') }} menyediakan layanan rental PlayStation dengan sistem voucher digital dan live monitoring. Ketentuan penggunaan:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Waktu rental sesuai dengan voucher yang dibeli</li>
                        <li>Pengguna wajib merawat peralatan dengan baik</li>
                        <li>Kerusakan akibat kelalaian pengguna menjadi tanggung jawab penyewa</li>
                        <li>Pengembalian harus tepat waktu sesuai durasi rental</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">3. Akun Member</h4>
                    <p class="mb-4">Untuk menggunakan layanan, Anda harus:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Berusia minimal 17 tahun atau memiliki izin orang tua/wali</li>
                        <li>Memberikan informasi yang akurat dan lengkap</li>
                        <li>Menjaga kerahasiaan password akun</li>
                        <li>Bertanggung jawab atas aktivitas di akun Anda</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">4. Pembayaran dan Voucher</h4>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Pembayaran dapat dilakukan melalui berbagai metode yang tersedia</li>
                        <li>Voucher yang telah dibeli tidak dapat dikembalikan</li>
                        <li>Voucher memiliki masa berlaku sesuai yang tertera</li>
                        <li>Voucher tidak dapat dipindahtangankan atau dijual kembali</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">5. Pembatalan dan Pengembalian Dana</h4>
                    <p class="mb-4">Pembatalan dapat dilakukan dengan ketentuan:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Pembatalan minimal 2 jam sebelum waktu rental</li>
                        <li>Pengembalian dana akan diproses dalam 3-5 hari kerja</li>
                        <li>Potongan biaya administrasi sebesar 10% untuk pembatalan</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">6. Larangan Penggunaan</h4>
                    <p class="mb-4">Anda dilarang untuk:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Menggunakan layanan untuk tujuan ilegal</li>
                        <li>Merusak atau memodifikasi peralatan</li>
                        <li>Membagikan atau menjual akses ke pihak lain</li>
                        <li>Melakukan aktivitas yang merugikan pihak lain</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">7. Tanggung Jawab</h4>
                    <p class="mb-4">{{ config('app.name', 'LOS SEKOLITOS') }} tidak bertanggung jawab atas:</p>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Kehilangan data game yang tersimpan</li>
                        <li>Gangguan layanan di luar kendali kami</li>
                        <li>Kerusakan akibat force majeure</li>
                    </ul>

                    <h4 class="text-lg font-semibold mt-6 mb-2">8. Perubahan Syarat</h4>
                    <p class="mb-4">Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diumumkan melalui website dan email terdaftar.</p>

                    <h4 class="text-lg font-semibold mt-6 mb-2">9. Kontak</h4>
                    <p class="mb-4">Untuk pertanyaan tentang Terms of Service, hubungi:</p>
                    <p class="mb-2"><strong>Email:</strong> support@rentalpees.com</p>
                    <p class="mb-2"><strong>Telepon:</strong> +62 812-3456-7890</p>
                </div>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0 hover:from-sky-600 hover:to-blue-700">Tutup</button>
                    </form>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
