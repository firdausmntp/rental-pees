<div class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 p-4 md:p-6">
    <!-- Header dengan gradient dan animasi -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-500 p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 text-center">
            <i class='bx bxs-coupon text-7xl text-white animate-bounce mb-4'></i>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">Redeem Voucher</h1>
            <p class="text-white/90 text-lg">Gunakan voucher member untuk bermain PlayStation</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success shadow-lg mb-6 animate-pulse">
            <i class='bx bx-check-circle text-2xl'></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-info shadow-lg mb-6">
            <i class='bx bx-info-circle text-2xl'></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error shadow-lg mb-6">
            <i class='bx bx-error-circle text-2xl'></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <!-- Step 1: Input Kode Voucher -->
        <div class="card bg-gradient-to-br from-base-100 to-base-200 shadow-2xl mb-6 border-2 border-primary/20">
            <div class="card-body">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">1</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Masukkan Kode Voucher</h2>
                        <p class="text-base-content/60">Ketik atau scan kode voucher member</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="form-control flex-1">
                        <input 
                            type="text" 
                            wire:model="kode_voucher" 
                            placeholder="Contoh: VCH-ABCD1234" 
                            class="input input-bordered input-lg text-center font-mono tracking-wider uppercase"
                            @if($voucher) disabled @endif
                        >
                    </div>
                    @if(!$voucher)
                        <button wire:click="checkVoucher" class="btn btn-primary btn-lg gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 border-0 hover:scale-105 transition-transform">
                            <i class='bx bx-search text-xl'></i>
                            Cek Voucher
                        </button>
                    @else
                        <button wire:click="resetForm" class="btn btn-outline btn-lg gap-2 px-6">
                            <i class='bx bx-refresh text-xl'></i>
                            <span class="font-semibold">Reset</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Step 2: Detail Voucher (muncul setelah cek) -->
        @if($voucher)
            <div class="card bg-gradient-to-br from-base-100 to-base-200 shadow-2xl mb-6 border-2 border-success animate-in">
                <div class="card-body">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">2</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">Detail Voucher</h2>
                            <p class="text-base-content/60">Informasi voucher yang akan digunakan</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white text-center">
                                <p class="text-white/70 text-sm mb-2">KODE VOUCHER</p>
                                <p class="font-mono text-2xl font-bold tracking-wider">{{ $voucher->kode_voucher }}</p>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center">
                                    <i class='bx bx-user text-primary text-2xl'></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-base-content/60">{{ $voucher->member ? 'Nama Member' : 'Nama Pembeli' }}</p>
                                    <p class="font-bold text-lg">
                                        {{ $voucher->member->name ?? $voucher->nama_pembeli ?? 'Tanpa nama' }}
                                    </p>
                                    @if(!$voucher->member)
                                        <span class="badge badge-ghost badge-sm gap-1 mt-1">
                                            <i class='bx bx-user-voice'></i>
                                            Non-member
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    @if($voucher->status_pembayaran === 'paid')
                                        <span class="badge badge-success gap-2">
                                            <i class='bx bx-check-circle'></i>
                                            Paid
                                        </span>
                                    @elseif($voucher->status_pembayaran === 'pending')
                                        <span class="badge badge-warning gap-2">
                                            <i class='bx bx-time'></i>
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge badge-error gap-2">
                                            <i class='bx bx-x-circle'></i>
                                            Failed
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center">
                                    <i class='bx bx-time text-secondary text-2xl'></i>
                                </div>
                                <div>
                                    <p class="text-sm text-base-content/60">Durasi Bermain</p>
                                    @if($voucher->metode_pembayaran === 'kompromi' && $voucher->durasi_menit)
                                        <p class="font-bold text-lg">{{ $voucher->durasi_menit }} Menit</p>
                                        <p class="text-xs text-base-content/50">(≈ {{ $voucher->durasi_jam }} jam)</p>
                                    @else
                                        <p class="font-bold text-lg">{{ $voucher->durasi_jam }} Jam</p>
                                    @endif
                                    @if($voucher->metode_pembayaran === 'kompromi')
                                        <span class="badge badge-info badge-sm gap-1 mt-2">
                                            <i class='bx bx-handshake'></i>
                                            Kompromi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center">
                                    <i class='bx bx-money text-accent text-2xl'></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-base-content/60">Jenis Tarif</p>
                                    @if($voucher->tarif)
                                        <p class="font-bold text-lg">{{ $voucher->tarif->tipe_ps }} - {{ $voucher->tarif->jenis_tarif }}</p>
                                        <p class="text-xs text-base-content/50">Rp {{ number_format($voucher->harga_per_jam, 0, ',', '.') }}/jam</p>
                                    @elseif($voucher->harga_per_jam > 0)
                                        <p class="font-bold text-lg text-warning">Rp {{ number_format($voucher->harga_per_jam, 0, ',', '.') }}/jam</p>
                                        <p class="text-xs text-warning">Snapshot harga</p>
                                    @else
                                        <p class="font-bold text-lg text-error">Tarif tidak ditemukan</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-info/20 flex items-center justify-center">
                                    <i class='bx bx-calendar text-info text-2xl'></i>
                                </div>
                                <div>
                                    <p class="text-sm text-base-content/60">Tanggal Beli</p>
                                    <p class="font-bold">{{ $voucher->tanggal_beli->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-warning/20 flex items-center justify-center">
                                    <i class='bx bx-calendar-x text-warning text-2xl'></i>
                                </div>
                                <div>
                                    <p class="text-sm text-base-content/60">Expired Pada</p>
                                    <p class="font-bold">{{ $voucher->expired_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success mt-6">
                        <i class='bx bx-check-circle text-2xl'></i>
                        <span class="font-semibold">Voucher valid! Silakan pilih PlayStation untuk melanjutkan.</span>
                    </div>
                </div>
            </div>

            <!-- Step 3: Pilih PlayStation -->
            @if($showRedeemForm)
                <div class="card bg-gradient-to-br from-base-100 to-base-200 shadow-2xl border-2 border-primary/20 animate-in">
                    <div class="card-body">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">3</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold">Pilih PlayStation</h2>
                                <p class="text-base-content/60">Pilih PlayStation yang tersedia untuk bermain</p>
                            </div>
                        </div>

                        @if($voucher && $voucher->tarif)
                            <div class="alert alert-info mb-6">
                                <i class='bx bx-info-circle text-2xl'></i>
                                <span class="font-semibold">PlayStation telah difilter berdasarkan tipe voucher: <span class="badge badge-primary">{{ $voucher->tarif->tipe_ps }}</span></span>
                            </div>
                        @endif

                        <form wire:submit.prevent="redeem">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                @forelse($playstations as $ps)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="playstation" wire:model="playstation_id" value="{{ $ps->id }}" class="hidden peer" required>
                                        <div class="card bg-base-200 border-2 border-transparent peer-checked:border-emerald-600 peer-checked:bg-emerald-600/10 hover:border-emerald-400 transition-all transform hover:scale-105 h-full">
                                            <div class="card-body p-4">
                                                <div class="flex items-start gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" viewBox="0 0 24 24" class="text-emerald-600 flex-shrink-0">
                                                        <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="badge badge-primary badge-sm mb-1">{{ $ps->tipe }}-{{ str_pad($ps->nomor_ps, 3, '0', STR_PAD_LEFT) }}</div>
                                                        <h3 class="font-bold text-lg text-emerald-600 leading-tight">{{ $ps->nama_konsol ?? $ps->tipe . ' #' . $ps->nomor_ps }}</h3>
                                                        <div class="mt-2 space-y-1">
                                                            @if($ps->lokasi)
                                                                <div class="flex items-center gap-2 text-sm">
                                                                    <i class='bx bx-map text-info'></i>
                                                                    <span class="text-base-content/80">{{ $ps->lokasi }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex items-center gap-2 text-sm">
                                                                <i class='bx bx-joystick text-warning'></i>
                                                                <span class="text-base-content/80">{{ $ps->jumlah_stik ?? 2 }} Stik Controller</span>
                                                            </div>
                                                            @if($ps->keterangan)
                                                                <div class="flex items-start gap-2 text-sm">
                                                                    <i class='bx bx-info-circle text-accent'></i>
                                                                    <span class="text-base-content/60 text-xs">{{ $ps->keterangan }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="badge badge-success badge-sm mt-3 gap-1">
                                                            <i class='bx bx-check-circle'></i>
                                                            Tersedia
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-full">
                                        <div class="alert alert-warning">
                                            <i class='bx bx-error text-2xl'></i>
                                            <span>Tidak ada PlayStation yang tersedia saat ini</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            @if(count($playstations) > 0)
                                <button type="submit" class="btn btn-lg btn-primary w-full gap-3 bg-gradient-to-r from-emerald-600 to-teal-600 border-0 hover:scale-105 transition-transform">
                                    <i class='bx bx-check-circle text-2xl'></i>
                                    <span class="text-xl font-bold">REDEEM VOUCHER & MULAI BERMAIN</span>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <style>
        @keyframes animate-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: animate-in 0.5s ease-out;
        }
    </style>
</div>
