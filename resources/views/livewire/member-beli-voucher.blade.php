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

                <form wire:submit.prevent="preparePayment">
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
                            <div class="flex-1 flex flex-col items-center">
                                <input type="number" wire:model.live="durasi_jam" class="input input-bordered input-lg text-center text-4xl font-bold w-full max-w-xs bg-base-100 text-base-content" required>
                                <p class="text-sm text-base-content/60 mt-2">Jam (Real: {{ $durasi_jam }} jam 5 menit)</p>
                            </div>
                            <button type="button" wire:click="incrementDurasi" class="btn btn-circle btn-lg btn-primary">
                                <i class='bx bx-plus text-2xl'></i>
                            </button>
                        </div>
                        @error('durasi_jam') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <!-- Total Harga Display -->
                    <div class="card bg-base-200 border-2 border-primary/20 mb-6">
                        <div class="card-body">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-bold text-lg">Total Pembayaran</h3>
                                    <p class="text-sm text-base-content/60">
                                        @if($selectedTarif)
                                            Rp {{ number_format($selectedTarif->harga_per_jam, 0, ',', '.') }} x {{ $durasi_jam }} Jam
                                        @else
                                            Pilih paket terlebih dahulu
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-primary">
                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-bold text-lg">Metode Pembayaran</span>
                        </label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="metode_pembayaran" value="cash" class="hidden peer" required>
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
                                        <p class="text-sm text-base-content/60">Scan & bayar (cek manual)</p>
                                    </div>
                                </div>
                            </label>
                            @if(in_array('pakasir', $availablePaymentMethods, true))
                                <label class="cursor-pointer md:col-span-2">
                                    <input type="radio" wire:model.live="metode_pembayaran" value="pakasir" class="hidden peer">
                                    <div class="card bg-base-200 border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/10 hover:border-primary/50 transition-all">
                                        <div class="card-body items-center text-center p-6">
                                            <i class='bx bx-credit-card text-6xl text-primary'></i>
                                            <h3 class="text-xl font-bold mt-3">Pakasir (Otomatis)</h3>
                                            <p class="text-sm text-base-content/60">QRIS, e-Wallet, Virtual Account.</p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                            @if(in_array('midtrans', $availablePaymentMethods, true))
                                <label class="cursor-pointer md:col-span-2">
                                    <input type="radio" wire:model.live="metode_pembayaran" value="midtrans" class="hidden peer">
                                    <div class="card bg-base-200 border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/10 hover:border-primary/50 transition-all">
                                        <div class="card-body items-center text-center p-6">
                                            <i class='bx bx-credit-card text-6xl text-primary'></i>
                                            <h3 class="text-xl font-bold mt-3">Midtrans (Otomatis)</h3>
                                            <p class="text-sm text-base-content/60">Gopay, OVO, ShopeePay, VA Bank, dll.</p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        </div>
                        @error('metode_pembayaran') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    @if($metode_pembayaran === 'qris')
                        <div class="alert alert-info mb-6">
                            <i class='bx bx-info-circle text-2xl'></i>
                            <div>
                                <h4 class="font-bold text-base">Pembayaran QRIS Manual</h4>
                                <p class="text-sm text-base-content/70">Silakan klik tombol <strong>BELI VOUCHER</strong> di bawah. QRIS dan nominal unik akan muncul di pop-up selanjutnya.</p>
                            </div>
                        </div>
                    @endif

                    @if($metode_pembayaran === 'pakasir')
                        <div class="alert alert-info mb-6">
                            <i class='bx bx-credit-card text-2xl'></i>
                            <div>
                                <h4 class="font-bold text-base">Pembayaran Otomatis Pakasir</h4>
                                <p class="text-sm text-base-content/70">Mendukung QRIS, e-Wallet, dan Virtual Account dengan verifikasi otomatis.</p>
                            </div>
                        </div>
                    @endif

                    @if(in_array('midtrans', $availablePaymentMethods, true) && $metode_pembayaran === 'midtrans')
                        <div class="alert alert-info mb-6">
                            <i class='bx bx-credit-card text-2xl'></i>
                            <div>
                                <h4 class="font-bold text-base">Pembayaran Otomatis Midtrans</h4>
                                <p class="text-sm text-base-content/70">Mendukung berbagai metode pembayaran (E-Wallet, VA, dll). Verifikasi otomatis.</p>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-lg w-full gap-3 bg-gradient-to-r from-blue-600 to-purple-600 border-0 hover:scale-105 transition-transform" wire:loading.attr="disabled">
                        <span wire:loading.remove class="flex items-center gap-3">
                            <i class='bx bx-shopping-bag text-2xl'></i>
                            <span class="text-xl font-bold">BELI VOUCHER</span>
                        </span>
                        <span wire:loading class="flex items-center gap-3">
                            <span class="loading loading-spinner loading-md"></span>
                            <span class="text-xl font-bold">MEMPROSES...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- QRIS Modal -->
    @if($showQrisModal)
        <div class="modal modal-open">
            <div class="modal-box max-w-2xl bg-base-100">
                <h3 class="font-bold text-2xl mb-4 flex items-center gap-2">
                    <i class='bx bx-qr text-info text-3xl'></i>
                    Konfirmasi Pembayaran QRIS
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex flex-col items-center gap-4">
                        <div class="bg-white p-4 rounded-xl border border-base-200 shadow-lg">
                            @if($qrisData)
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrisData) !!}
                            @else
                                <div class="w-48 h-48 flex items-center justify-center bg-base-200 rounded-lg">
                                    <span class="loading loading-spinner loading-lg"></span>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-base-content/60 text-center">
                            Scan QR di atas lalu transfer sesuai nominal unik agar kasir mudah memverifikasi pembayaran manual.
                        </p>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="bg-base-200 rounded-xl p-4">
                            <p class="text-xs text-base-content/70">Nominal Unik</p>
                            <p class="text-3xl font-bold text-warning mt-1">
                                @if($previewQrisNominal)
                                    Rp {{ number_format($previewQrisNominal, 0, ',', '.') }}
                                @else
                                    Sedang menyiapkan nominal...
                                @endif
                            </p>
                            <p class="text-xs text-base-content/60 mt-2">Nominal sudah termasuk kode unik. Mohon transfer sesuai angka yang tertera.</p>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Upload Bukti Pembayaran</span>
                            </label>
                            <input type="file" accept="image/*" class="file-input file-input-bordered file-input-info" wire:model="buktiPembayaran">
                            @error('buktiPembayaran') <span class="text-error text-xs mt-2">{{ $message }}</span> @enderror
                            @if($buktiPembayaran)
                                <div class="mt-3">
                                    <span class="text-xs text-base-content/60">Preview:</span>
                                    <img src="{{ $buktiPembayaran->temporaryUrl() }}" class="mt-2 rounded-lg border border-base-200 max-h-48 object-contain" alt="Preview bukti pembayaran">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" wire:click="closeQrisModal">Bayar Nanti</button>
                    <button type="button" class="btn btn-primary" wire:click="confirmQrisPayment" wire:loading.attr="disabled">
                        <span wire:loading.remove>Konfirmasi &amp; Upload Bukti</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
