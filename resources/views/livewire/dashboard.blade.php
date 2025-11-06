<div class="min-h-screen bg-base-200 p-6">
    <!-- Hero Header with Gradient -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary via-primary/90 to-accent p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                Dashboard Admin 
                <i class='bx bxs-dashboard animate-pulse'></i>
            </h1>
            <p class="text-white/90 text-lg">Selamat datang, {{ $user->name }}!</p>
            <p class="text-white/70 text-sm mt-1">{{ Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Income & Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today Income -->
        <div class="card bg-gradient-to-br from-success to-success/80 text-white shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm mb-1">Pemasukan Hari Ini</p>
                        <h2 class="text-3xl font-bold">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h2>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                        <i class='bx bx-money text-4xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Income -->
        <div class="card bg-gradient-to-br from-info to-info/80 text-white shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm mb-1">Pemasukan Bulan Ini</p>
                        <h2 class="text-3xl font-bold">Rp {{ number_format($monthIncome, 0, ',', '.') }}</h2>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                        <i class='bx bx-trending-up text-4xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="card bg-gradient-to-br from-warning to-warning/80 text-white shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm mb-1">Transaksi Bulan Ini</p>
                        <h2 class="text-3xl font-bold">{{ $monthTransactions }}</h2>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                        <i class='bx bx-receipt text-4xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Members -->
        <div class="card bg-gradient-to-br from-secondary to-secondary/80 text-white shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm mb-1">Member Aktif</p>
                        <h2 class="text-3xl font-bold">{{ $totalMembers }}</h2>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                        <i class='bx bx-group text-4xl'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PlayStation Stats -->
    <div class="card bg-base-100 shadow-xl mb-8">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4">
                <i class='bx bx-joystick-alt text-primary'></i>
                Status PlayStation
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat bg-base-200 rounded-xl">
                    <div class="stat-figure text-primary">
                        <i class='bx bxs-devices text-4xl'></i>
                    </div>
                    <div class="stat-title">Total PS</div>
                    <div class="stat-value text-primary">{{ $psStats['total'] }}</div>
                </div>
                
                <div class="stat bg-base-200 rounded-xl">
                    <div class="stat-figure text-success">
                        <i class='bx bx-check-circle text-4xl'></i>
                    </div>
                    <div class="stat-title">Tersedia</div>
                    <div class="stat-value text-success">{{ $psStats['tersedia'] }}</div>
                </div>
                
                <div class="stat bg-base-200 rounded-xl">
                    <div class="stat-figure text-warning">
                        <i class='bx bx-play-circle text-4xl'></i>
                    </div>
                    <div class="stat-title">Dipakai</div>
                    <div class="stat-value text-warning">{{ $psStats['dipakai'] }}</div>
                </div>
                
                <div class="stat bg-base-200 rounded-xl">
                    <div class="stat-figure text-error">
                        <i class='bx bx-error-circle text-4xl'></i>
                    </div>
                    <div class="stat-title">Rusak</div>
                    <div class="stat-value text-error">{{ $psStats['rusak'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Monitoring Active Transactions -->
    <div class="card bg-base-100 shadow-xl" wire:poll.60s>
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4">
                <i class='bx bx-tv text-accent animate-pulse'></i>
                Live Monitoring - Transaksi Berlangsung
                <span class="badge badge-sm badge-info ml-2">Auto-refresh 60s</span>
            </h2>

            @if($activeTransactions->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($activeTransactions as $transaksi)
                        <div class="card bg-base-200 border-2 border-primary shadow-lg">
                            <div class="card-body">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-bold text-lg">{{ $transaksi->playStation->kode_ps }}</h3>
                                        <p class="text-sm text-base-content/70">{{ $transaksi->playStation->nama_konsol }}</p>
                                    </div>
                                    <div class="badge badge-lg badge-success">
                                        {{ $transaksi->playStation->tipe }}
                                    </div>
                                </div>

                                <div class="divider my-1"></div>

                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-user text-primary'></i>
                                        <span class="text-sm">{{ $transaksi->pelanggan->nama }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-time text-info'></i>
                                        <span class="text-sm">{{ Carbon\Carbon::parse($transaksi->waktu_mulai)->format('H:i') }} - {{ Carbon\Carbon::parse($transaksi->waktu_selesai)->format('H:i') }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-purchase-tag text-success'></i>
                                        <span class="text-sm">
                                            @if($transaksi->total_biaya > 0)
                                                Rp {{ number_format($transaksi->tarif_per_jam, 0, ',', '.') }}/jam
                                            @else
                                                <span class="badge badge-primary badge-sm">Redeem Voucher</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="mb-2 flex justify-between text-sm">
                                        <span>Sisa Waktu:</span>
                                        <span class="font-bold">{{ $transaksi->time_remaining->format('%H:%I:%S') }}</span>
                                    </div>
                                    <progress class="progress progress-primary" value="{{ $transaksi->progress_percentage }}" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-tv text-6xl text-base-content/20'></i>
                    <p class="text-base-content/60 mt-4">Tidak ada transaksi yang sedang berlangsung</p>
                </div>
            @endif
        </div>
    </div>
</div>
