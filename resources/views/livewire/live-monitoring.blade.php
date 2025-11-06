<div class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 p-4 md:p-6" wire:poll.60s>
    <!-- Header with Live Indicator -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-600 via-orange-600 to-yellow-500 p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                        <div class="relative">
                            <i class='bx bx-tv text-5xl'></i>
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>
                        </div>
                        Live Monitoring
                    </h1>
                    <p class="text-white/90 text-lg flex items-center gap-2">
                        <span class="badge badge-sm bg-white/20 text-white border-0">LIVE</span>
                        Real-time status semua PlayStation
                    </p>
                </div>
                <div class="text-white/70 text-sm">
                    <i class='bx bx-time-five'></i> Update setiap 1 menit
                </div>
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card bg-gradient-to-br from-primary to-primary/80 text-white shadow-xl transform hover:scale-105 transition">
            <div class="card-body items-center text-center p-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                </svg>
                <p class="text-white/80 text-sm mt-2">Total PS</p>
                <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-success to-success/80 text-white shadow-xl transform hover:scale-105 transition">
            <div class="card-body items-center text-center p-6">
                <i class='bx bx-check-circle text-5xl'></i>
                <p class="text-white/80 text-sm mt-2">Tersedia</p>
                <p class="text-4xl font-bold">{{ $stats['tersedia'] }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-info to-info/80 text-white shadow-xl transform hover:scale-105 transition">
            <div class="card-body items-center text-center p-6">
                <i class='bx bx-play-circle text-5xl animate-pulse'></i>
                <p class="text-white/80 text-sm mt-2">Digunakan</p>
                <p class="text-4xl font-bold">{{ $stats['digunakan'] }}</p>
            </div>
        </div>
        <div class="card bg-gradient-to-br from-warning to-warning/80 text-white shadow-xl transform hover:scale-105 transition">
            <div class="card-body items-center text-center p-6">
                <i class='bx bx-wrench text-5xl'></i>
                <p class="text-white/80 text-sm mt-2">Maintenance</p>
                <p class="text-4xl font-bold">{{ $stats['maintenance'] }}</p>
            </div>
        </div>
    </div>

    <!-- Active Transactions -->
    @if($activeTransactions->count() > 0)
        <div class="card bg-base-100 shadow-2xl mb-8 border-2 border-info">
            <div class="card-body">
                <h2 class="card-title text-3xl mb-6 flex items-center gap-3">
                    <i class='bx bx-time text-info text-4xl animate-pulse'></i>
                    Sesi Aktif ({{ $activeTransactions->count() }})
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($activeTransactions as $transaksi)
                        <div class="card bg-gradient-to-br from-base-200 to-base-300 border-2 
                            @if($transaksi->is_overtime) border-error animate-pulse @else border-info @endif">
                            <div class="card-body p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-2xl font-bold flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 24 24" class="text-primary">
                                                <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                                            </svg>
                                            PS {{ $transaksi->playStation->nomor_ps }}
                                        </h3>
                                        <p class="text-sm text-base-content/60">{{ $transaksi->tarif->jenis_tarif }}</p>
                                    </div>
                                    <div class="badge badge-lg 
                                        @if($transaksi->is_overtime) badge-error @else badge-info @endif gap-2">
                                        <i class='bx bxs-circle text-xs animate-pulse'></i>
                                        @if($transaksi->is_overtime) OVERTIME @else PLAYING @endif
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span>Progress</span>
                                        <span>{{ number_format($transaksi->progress_percentage, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-base-300 rounded-full h-3 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 
                                            @if($transaksi->is_overtime) bg-gradient-to-r from-error to-error/70 animate-pulse
                                            @else bg-gradient-to-r from-info to-info/70 @endif"
                                            style="width: {{ $transaksi->progress_percentage }}%"></div>
                                    </div>
                                </div>

                                <!-- Time Info -->
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="bg-base-100/50 rounded-lg p-3">
                                        <p class="text-base-content/60 text-xs">Mulai</p>
                                        <p class="font-bold">{{ \Carbon\Carbon::parse($transaksi->waktu_mulai)->format('H:i') }}</p>
                                    </div>
                                    <div class="bg-base-100/50 rounded-lg p-3">
                                        <p class="text-base-content/60 text-xs">Selesai</p>
                                        <p class="font-bold">{{ \Carbon\Carbon::parse($transaksi->waktu_selesai)->format('H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Remaining Time -->
                                <div class="mt-3 text-center">
                                    @if($transaksi->is_overtime)
                                        <div class="bg-error/10 rounded-lg p-3">
                                            <p class="text-error text-xs font-semibold">OVERTIME</p>
                                            <p class="text-error text-2xl font-bold">
                                                +{{ $transaksi->time_remaining->h }}h {{ $transaksi->time_remaining->i }}m
                                            </p>
                                        </div>
                                    @else
                                        <div class="bg-info/10 rounded-lg p-3">
                                            <p class="text-info text-xs font-semibold">WAKTU TERSISA</p>
                                            <p class="text-info text-3xl font-bold">
                                                {{ $transaksi->time_remaining->h }}:{{ str_pad($transaksi->time_remaining->i, 2, '0', STR_PAD_LEFT) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- All PlayStation Grid -->
    <div class="card bg-base-100 shadow-2xl">
        <div class="card-body">
            <h2 class="card-title text-3xl mb-6 flex items-center gap-3">
                <i class='bx bx-grid-alt text-primary text-4xl'></i>
                Semua PlayStation
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($playstations as $ps)
                    <div class="card bg-gradient-to-br from-base-200 to-base-300 shadow-xl border-2 transform hover:scale-105 transition-all
                        @if($ps->status === 'tersedia') border-success
                        @elseif($ps->status === 'digunakan') border-info
                        @else border-warning @endif">
                        
                        <div class="card-body items-center text-center p-4">
                            <!-- PS Icon with Status -->
                            <div class="relative mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 24 24"
                                    class="@if($ps->status === 'tersedia') text-success
                                    @elseif($ps->status === 'digunakan') text-info
                                    @else text-warning @endif">
                                    <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                                </svg>
                                
                                @if($ps->status === 'digunakan')
                                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-info opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-info"></span>
                                    </span>
                                @endif
                            </div>

                            <!-- PS Number -->
                            <h3 class="text-2xl font-bold">PS {{ $ps->nomor_ps }}</h3>

                            <!-- Status Badge -->
                            <div class="badge badge-sm w-full 
                                @if($ps->status === 'tersedia') badge-success
                                @elseif($ps->status === 'digunakan') badge-info
                                @else badge-warning @endif">
                                {{ strtoupper($ps->status) }}
                            </div>

                            <!-- Active Transaction Info -->
                            @if($ps->status === 'digunakan' && $ps->transaksis->first())
                                @php $transaksi = $ps->transaksis->first(); @endphp
                                <div class="mt-2 text-xs">
                                    <p class="text-base-content/60">Sampai:</p>
                                    <p class="font-bold">{{ \Carbon\Carbon::parse($transaksi->waktu_selesai)->format('H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
