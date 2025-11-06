<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="rental">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | {{ config('app.name', 'RENTAL PEES') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-base-200 via-base-100 to-base-200">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <div class="card bg-base-100 shadow-2xl">
                <div class="card-body items-center text-center p-8 md:p-12">
                    <!-- Error Icon -->
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-error/20 rounded-full blur-3xl"></div>
                        <div class="relative w-32 h-32 bg-gradient-to-br from-error to-error/80 rounded-full flex items-center justify-center">
                            <i class='bx bx-error-circle text-white text-7xl'></i>
                        </div>
                    </div>

                    <!-- Error Code -->
                    <h1 class="text-8xl md:text-9xl font-bold text-error mb-2">404</h1>
                    
                    <!-- Error Message -->
                    <h2 class="text-2xl md:text-3xl font-bold text-base-content mb-4">
                        Halaman Tidak Ditemukan
                    </h2>
                    
                    <p class="text-base-content/70 mb-8 max-w-md">
                        Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau URL salah.
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="window.history.back()" class="btn btn-primary gap-2">
                            <i class='bx bx-arrow-back text-lg'></i>
                            Kembali ke Halaman Sebelumnya
                        </button>
                        
                        <a href="{{ auth()->check() ? 
                            (auth()->user()->isOwner() ? route('owner.dashboard') : 
                            (auth()->user()->isKaryawan() ? route('karyawan.dashboard') : 
                            (auth()->user()->isMember() ? route('member.dashboard') : route('dashboard')))) : 
                            '/' 
                        }}" class="btn btn-outline gap-2">
                            <i class='bx bx-home text-lg'></i>
                            Ke Halaman Utama
                        </a>
                    </div>

                    <!-- Additional Help -->
                    <div class="divider my-8"></div>
                    
                    <div class="alert alert-info">
                        <i class='bx bx-info-circle text-xl'></i>
                        <div class="text-left">
                            <h3 class="font-bold">Butuh Bantuan?</h3>
                            <p class="text-sm">Jika masalah berlanjut, silakan hubungi administrator atau coba akses menu lain dari dashboard.</p>
                        </div>
                    </div>

                    <!-- PlayStation Icon Decoration -->
                    <div class="mt-8 opacity-20">
                        <svg class="w-24 h-24 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.985 2.596v17.548l3.91-1.31V6.688l5.458 2.572v5.53l3.455 1.264V3.854L8.985 2.596zm11.656 8.559l-3.455-1.264v5.53l3.455 1.264v-5.53zm-17.394 5.65l1.725-.644V8.538l2.74 1.284v8.708l-4.465-2.126z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-base-content/60 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'RENTAL PEES') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
