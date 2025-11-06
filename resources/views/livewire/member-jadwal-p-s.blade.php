<div class="min-h-screen bg-base-200 p-4 md:p-6">
    <!-- Header -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary via-accent to-secondary p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                <i class='bx bx-calendar-check text-5xl'></i>
                Jadwal Ketersediaan PlayStation
            </h1>
            <p class="text-white/90 text-lg">Cek PlayStation mana yang sedang tersedia untuk disewa</p>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i class='bx bxs-devices text-2xl text-primary'></i>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/60">Total Unit</p>
                        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center">
                        <i class='bx bxs-check-circle text-2xl text-success'></i>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/60">Tersedia</p>
                        <p class="text-2xl font-bold text-success">{{ $stats['tersedia'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-warning/10 flex items-center justify-center">
                        <i class='bx bxs-time text-2xl text-warning'></i>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/60">Sedang Dipakai</p>
                        <p class="text-2xl font-bold text-warning">{{ $stats['dipakai'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-error/10 flex items-center justify-center">
                        <i class='bx bxs-x-circle text-2xl text-error'></i>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/60">Maintenance</p>
                        <p class="text-2xl font-bold text-error">{{ $stats['rusak'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PlayStation Lists -->
    @foreach(['PS5', 'PS4', 'PS3'] as $tipe)
        @if($psGrouped[$tipe]->count() > 0)
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br 
                        @if($tipe === 'PS5') from-sky-500 to-blue-600
                        @elseif($tipe === 'PS4') from-blue-500 to-cyan-600
                        @else from-slate-600 to-slate-800 @endif
                        flex items-center justify-center shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24" class="text-white">
                            <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">{{ $tipe }} 
                        <span class="text-base-content/60 text-lg">({{ $psGrouped[$tipe]->count() }} Unit)</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($psGrouped[$tipe] as $ps)
                        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all 
                            @if($ps->status === 'tersedia') border-2 border-success
                            @elseif($ps->status === 'dipakai') border-2 border-warning
                            @else border-2 border-error @endif">
                            <div class="card-body">
                                <!-- Status Badge -->
                                <div class="flex justify-between items-start mb-3">
                                    <span class="badge badge-lg 
                                        @if($ps->status === 'tersedia') badge-success
                                        @elseif($ps->status === 'dipakai') badge-warning
                                        @else badge-error @endif gap-2">
                                        <i class='bx bxs-circle text-xs'></i>
                                        {{ strtoupper($ps->status) }}
                                    </span>
                                    <span class="badge badge-lg 
                                        @if($tipe === 'PS5') bg-gradient-to-r from-sky-500 to-blue-600 text-white border-0
                                        @elseif($tipe === 'PS4') bg-blue-600 text-white border-0
                                        @else bg-slate-700 text-white border-0 @endif">
                                        {{ $ps->kode_ps }}
                                    </span>
                                </div>

                                <!-- PS Name -->
                                <h3 class="text-xl font-bold mb-2">{{ $ps->nama_konsol }}</h3>

                                @if($ps->status === 'dipakai' && $ps->transaksis->first())
                                    @php
                                        $transaksi = $ps->transaksis->first();
                                        $now = \Carbon\Carbon::now();
                                        $start = \Carbon\Carbon::parse($transaksi->waktu_mulai);
                                        $end = \Carbon\Carbon::parse($transaksi->waktu_selesai);
                                        $diff = $end->diff($now);
                                        $isOvertime = $now->isAfter($end);
                                        
                                        // Calculate progress based on elapsed time
                                        $totalDuration = $end->diffInMinutes($start);
                                        $elapsed = $now->diffInMinutes($start);
                                        $progress = $totalDuration > 0 ? min(100, ($elapsed / $totalDuration) * 100) : 0;
                                        
                                        // Calculate remaining minutes for color coding
                                        $totalMinutes = $now->diffInMinutes($end);
                                    @endphp

                                    <!-- Active Session Info -->
                                    <div class="divider my-2"></div>
                                    
                                    <div class="bg-warning/10 rounded-lg p-3 mb-3">
                                        <p class="text-xs font-semibold text-warning mb-2">SEDANG DIGUNAKAN</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class='bx bx-user text-lg'></i>
                                            <span class="text-sm font-semibold">{{ $transaksi->pelanggan->nama ?? 'Pelanggan' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class='bx bx-time text-lg'></i>
                                            <span class="text-sm">
                                                Selesai: {{ $end->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Countdown Timer -->
                                    <div class="text-center mb-3">
                                        <p class="text-xs text-base-content/60 mb-1">Waktu Tersisa</p>
                                        @if($isOvertime)
                                            <p class="text-2xl font-bold text-error">
                                                OVERTIME +{{ $diff->format('%H:%I:%S') }}
                                            </p>
                                        @else
                                            <p class="text-3xl font-bold 
                                                @if($totalMinutes <= 10) text-error
                                                @elseif($totalMinutes <= 30) text-warning
                                                @else text-success @endif">
                                                {{ $diff->format('%H:%I:%S') }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-base-200 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all
                                            @if($isOvertime) bg-error
                                            @elseif($progress > 80) bg-warning
                                            @else bg-success @endif"
                                            style="width: {{ min(100, $progress) }}%">
                                        </div>
                                    </div>

                                @elseif($ps->status === 'tersedia')
                                    <div class="bg-success/10 rounded-lg p-4 text-center mt-4">
                                        <i class='bx bxs-check-circle text-4xl text-success mb-2'></i>
                                        <p class="text-sm font-semibold text-success">Siap Digunakan</p>
                                        <p class="text-xs text-base-content/60 mt-1">Beli voucher sekarang!</p>
                                    </div>

                                @else
                                    <div class="bg-error/10 rounded-lg p-4 text-center mt-4">
                                        <i class='bx bxs-wrench text-4xl text-error mb-2'></i>
                                        <p class="text-sm font-semibold text-error">Dalam Perbaikan</p>
                                        @if($ps->keterangan)
                                            <p class="text-xs text-base-content/60 mt-1">{{ $ps->keterangan }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    <!-- CTA Card -->
    <div class="card bg-gradient-to-r from-primary via-accent to-secondary shadow-2xl mt-8">
        <div class="card-body text-center">
            <h3 class="text-2xl font-bold text-white mb-2">Mau Main Sekarang?</h3>
            <p class="text-white/90 mb-4">Beli voucher dan redeem untuk mulai bermain!</p>
            <a href="{{ route('member.beli') }}" class="btn btn-lg bg-white text-primary hover:bg-white/90 border-0 gap-2 w-fit mx-auto">
                <i class='bx bx-shopping-bag text-2xl'></i>
                Beli Voucher Sekarang
            </a>
        </div>
    </div>
</div>

<!-- Auto Refresh setiap 10 detik -->
<script>
    setInterval(() => {
        @this.call('$refresh');
    }, 10000);
</script>
