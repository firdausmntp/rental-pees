<div class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 p-4 md:p-6">
    <!-- Hero Header -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-purple-600 to-pink-500 p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 text-center">
            <i class='bx bxs-shopping-bag text-7xl text-white animate-bounce mb-4'></i>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">Beli Voucher</h1>
            <p class="text-white/90 text-lg">Beli voucher online dan mainkan kapan saja!</p>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Form Beli Voucher -->
        <div class="card bg-gradient-to-br from-base-100 to-base-200 shadow-2xl border-2 border-primary/20">
                <div class="card-body">
                    <h2 class="card-title text-3xl mb-6 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center">
                            <i class='bx bx-cart text-white text-2xl'></i>
                        </div>
                        Pilih Paket
                    </h2>

                    <form wire:submit.prevent="beliVoucher">
                        <!-- Pilih Tarif -->
                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text font-bold text-lg">Pilih Jenis PlayStation</span>
                            </label>
                            <div class="grid md:grid-cols-2 gap-4">
                                @foreach($tarifs as $tarif)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="tarif_id" value="{{ $tarif->id }}" class="hidden peer" required>
                                        <div class="card bg-base-200 border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/10 hover:border-primary/50 transition-all transform hover:scale-105">
                                            <div class="card-body">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div>
                                                        <h3 class="text-xl font-bold">{{ $tarif->tipe_ps }}</h3>
                                                        <p class="text-sm text-base-content/60 flex items-center gap-1 mt-1">
                                                            <i class='bx bx-joystick-alt text-primary'></i>
                                                            PlayStation {{ str_replace('PS', '', $tarif->tipe_ps) }}
                                                        </p>
                                                    </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 24 24" class="text-primary">
                                                        <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                                                    </svg>
                                                </div>
                                                <div class="divider my-2"></div>
                                                <p class="text-2xl font-bold text-primary">
                                                    Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}
                                                    <span class="text-sm text-base-content/60">/jam</span>
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('tarif_id') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <!-- Durasi -->
                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text font-bold text-lg">Durasi Bermain</span>
                                <span class="label-text-alt text-info">+5 menit toleransi setup</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <button type="button" wire:click="decrementDurasi" class="btn btn-circle btn-lg btn-primary">
                                    <i class='bx bx-minus text-2xl'></i>
                                </button>
                                <div class="flex-1 text-center">
                                    <input type="number" wire:model.live.debounce.300ms="durasi_jam" min="1" max="24" 
                                           class="input input-bordered input-lg text-center text-4xl font-bold w-full max-w-xs" required>
                                    <p class="text-sm text-base-content/60 mt-2">Jam (Real: {{ $durasi_jam }} jam 5 menit)</p>
                                </div>
                                <button type="button" wire:click="incrementDurasi" class="btn btn-circle btn-lg btn-primary">
                                    <i class='bx bx-plus text-2xl'></i>
                                </button>
                            </div>
                            @error('durasi_jam') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <!-- Total Harga Preview -->
                        @if($totalHarga > 0)
                            <div class="alert alert-info mb-6">
                                <i class='bx bx-info-circle text-3xl'></i>
                                <div>
                                    <h4 class="font-bold text-lg">Total Pembayaran</h4>
                                    <p class="text-3xl font-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Metode Pembayaran -->
                        <div class="form-control mb-6">
                            <label class="label">
                                <span class="label-text font-bold text-lg">Metode Pembayaran</span>
                            </label>
                            <div class="grid md:grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="metode_pembayaran" value="cash" class="hidden peer">
                                    <div class="card bg-base-200 border-2 border-transparent peer-checked:border-success peer-checked:bg-success/10 hover:border-success/50 transition-all">
                                        <div class="card-body items-center text-center p-6">
                                            <i class='bx bx-money text-6xl text-success'></i>
                                            <h3 class="text-xl font-bold mt-3">Cash</h3>
                                            <p class="text-sm text-base-content/60">Bayar di kasir</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="metode_pembayaran" value="qris" class="hidden peer">
                                    <div class="card bg-base-200 border-2 border-transparent peer-checked:border-info peer-checked:bg-info/10 hover:border-info/50 transition-all">
                                        <div class="card-body items-center text-center p-6">
                                            <i class='bx bx-qr text-6xl text-info'></i>
                                            <h3 class="text-xl font-bold mt-3">QRIS</h3>
                                            <p class="text-sm text-base-content/60">Scan & bayar</p>
                                            <div class="badge badge-warning badge-sm mt-2">Demo</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('metode_pembayaran') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-full gap-3 bg-gradient-to-r from-blue-600 to-purple-600 border-0 hover:scale-105 transition-transform">
                            <i class='bx bx-shopping-bag text-2xl'></i>
                            <span class="text-xl font-bold">BELI VOUCHER</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
