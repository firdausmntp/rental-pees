<div class="min-h-screen bg-base-100 p-4 md:p-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-lg">
            <i class='bx bx-check-circle text-2xl'></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error mb-6 shadow-lg">
            <i class='bx bx-error-circle text-2xl'></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Hero Header -->
    <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary via-accent to-primary/80 p-8 shadow-xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                        <i class='bx bxs-dashboard text-5xl drop-shadow-lg'></i>
                        Dashboard Member
                    </h1>
                    <p class="text-white/95 text-lg">Selamat datang kembali, <span class="font-semibold">{{ auth()->user()->name }}</span>!</p>
                </div>
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card bg-base-200 shadow-lg hover:shadow-xl transition-all hover:scale-105">
            <div class="card-body items-center text-center p-6">
                <div class="w-12 h-12 rounded-full bg-success/20 flex items-center justify-center mb-3">
                    <i class='bx bxs-check-circle text-3xl text-success'></i>
                </div>
                <p class="text-base-content/70 text-sm">Voucher Aktif</p>
                <p class="text-4xl font-bold text-success">{{ $activeVouchersCount }}</p>
            </div>
        </div>
        <div class="card bg-base-200 shadow-lg hover:shadow-xl transition-all hover:scale-105">
            <div class="card-body items-center text-center p-6">
                <div class="w-12 h-12 rounded-full bg-warning/20 flex items-center justify-center mb-3">
                    <i class='bx bxs-time-five text-3xl text-warning'></i>
                </div>
                <p class="text-base-content/70 text-sm">Pending</p>
                <p class="text-4xl font-bold text-warning">{{ $pendingVouchersCount }}</p>
            </div>
        </div>
        <div class="card bg-base-200 shadow-lg hover:shadow-xl transition-all hover:scale-105">
            <div class="card-body items-center text-center p-6">
                <div class="w-12 h-12 rounded-full bg-info/20 flex items-center justify-center mb-3">
                    <i class='bx bxs-coupon text-3xl text-info'></i>
                </div>
                <p class="text-base-content/70 text-sm">Total Voucher</p>
                <p class="text-4xl font-bold text-info">{{ $totalVouchersCount }}</p>
            </div>
        </div>
        <div class="card bg-base-200 shadow-lg hover:shadow-xl transition-all hover:scale-105">
            <div class="card-body items-center text-center p-6">
                <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center mb-3">
                    <i class='bx bxs-dollar-circle text-3xl text-primary'></i>
                </div>
                <p class="text-base-content/70 text-sm">Total Belanja</p>
                <p class="text-xl font-bold text-primary">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Voucher List -->
    <div class="card bg-base-200 shadow-xl">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <h2 class="card-title text-2xl flex items-center gap-3">
                    <i class='bx bxs-coupon text-primary text-3xl'></i>
                    Voucher Saya
                </h2>
                <a href="{{ route('member.beli') }}" class="btn btn-primary btn-md gap-2 shadow-lg px-6">
                    <i class='bx bx-plus text-xl'></i>
                    <span class="font-semibold">Beli Voucher</span>
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button wire:click="$set('filterStatus', 'all')" 
                        class="btn btn-lg justify-start gap-3 transition-all shadow-md h-auto py-4
                        {{ $filterStatus === 'all' ? 'btn-primary' : 'btn-outline btn-primary hover:btn-primary' }}">
                        <i class='bx bx-list-ul text-3xl'></i>
                        <div class="text-left flex-1">
                            <div class="font-bold text-base">Semua</div>
                            <div class="text-xs opacity-70 mt-1">Voucher Aktif</div>
                        </div>
                    </button>
                    
                    <button wire:click="$set('filterStatus', 'aktif')" 
                        class="btn btn-lg justify-start gap-3 transition-all shadow-md h-auto py-4
                        {{ $filterStatus === 'aktif' ? 'btn-success' : 'btn-outline btn-success hover:btn-success' }}">
                        <i class='bx bx-check-circle text-3xl'></i>
                        <div class="text-left flex-1">
                            <div class="font-bold text-base">Aktif</div>
                            <div class="text-xs opacity-70 mt-1">Siap Pakai</div>
                        </div>
                    </button>
                    
                    <button wire:click="$set('filterStatus', 'pending')" 
                        class="btn btn-lg justify-start gap-3 transition-all shadow-md h-auto py-4
                        {{ $filterStatus === 'pending' ? 'btn-warning' : 'btn-outline btn-warning hover:btn-warning' }}">
                        <i class='bx bx-time text-3xl'></i>
                        <div class="text-left flex-1">
                            <div class="font-bold text-base">Pending</div>
                            <div class="text-xs opacity-70 mt-1">Menunggu</div>
                        </div>
                    </button>
                    
                    <button wire:click="$set('filterStatus', 'terpakai')" 
                        class="btn btn-lg justify-start gap-3 transition-all shadow-md h-auto py-4
                        {{ $filterStatus === 'terpakai' ? 'btn-neutral' : 'btn-outline hover:btn-neutral' }}">
                        <i class='bx bx-check text-3xl'></i>
                        <div class="text-left flex-1">
                            <div class="font-bold text-base">Terpakai</div>
                            <div class="text-xs opacity-70 mt-1">Riwayat</div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Vouchers Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($vouchers as $voucher)
                    <div class="card bg-base-100 border-2 hover:shadow-lg transition-all
                        @if($voucher->status_pembayaran === 'paid' && $voucher->status === 'aktif') border-success hover:border-success/70
                        @elseif($voucher->status_pembayaran === 'pending') border-warning hover:border-warning/70
                        @else border-base-300 hover:border-base-content/20 @endif">
                        <div class="card-body p-5">
                            <div class="flex justify-between items-start mb-4">
                                <div class="badge badge-lg font-semibold
                                    @if($voucher->status_pembayaran === 'paid') badge-success 
                                    @elseif($voucher->status_pembayaran === 'pending') badge-warning 
                                    @else badge-error @endif">
                                    {{ strtoupper($voucher->status_pembayaran) }}
                                </div>
                                <div class="badge badge-lg badge-outline border-2 font-medium">{{ strtoupper($voucher->status) }}</div>
                            </div>
                            
                            <div class="bg-base-200/70 rounded-xl p-4 mb-4">
                                <p class="text-xs text-base-content/60 mb-1">Kode Voucher</p>
                                @if($voucher->status_pembayaran === 'paid')
                                    <p class="font-mono text-xl font-bold text-base-content">{{ $voucher->kode_voucher }}</p>
                                @else
                                    <p class="text-sm text-warning font-medium">
                                        <i class='bx bx-time-five'></i>
                                        Kode akan muncul setelah pembayaran dikonfirmasi
                                    </p>
                                @endif
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-center gap-2 text-base-content/80">
                                    <i class='bx bx-time text-primary text-lg'></i>
                                    <span class="font-medium">{{ $voucher->durasi_jam }} jam</span>
                                </div>
                                <div class="flex items-center gap-2 text-base-content/80">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24" class="text-primary">
                                        <path d="M2.41 15.55c-.75.5-.5 1.45 1.1 1.9 1.65.55 3.45.7 5.2.4.1 0 .2-.05.25-.05v-1.7l-1.7.55c-.65.2-1.3.25-1.95.1-.5-.15-.4-.45.2-.7l3.45-1.2V13l-4.8 1.65c-.6.2-1.2.5-1.75.9M14 8.05v4.85c2.05 1 3.65 0 3.65-2.6s-.95-3.85-3.7-4.8C12.5 5 11 4.55 9.5 4.25v14.44l3.5 1.05V7.6c0-.55 0-.95.4-.8.55.15.6.7.6 1.25m6.5 6.35c-1.45-.5-3-.7-4.5-.55-.8.05-1.55.25-2.25.5l-.15.05v1.95l3.25-1.2c.65-.2 1.3-.25 1.95-.1.5.15.4.45-.2.7l-5 1.85v1.9l6.9-2.55c.5-.2.95-.45 1.35-.85.35-.5.2-1.2-1.35-1.7"></path>
                                    </svg>
                                    <span class="font-medium">{{ $voucher->tarif->tipe_ps }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-base-content/80">
                                    <i class='bx bx-money text-success text-lg'></i>
                                    <span class="font-semibold text-success">Rp {{ number_format($voucher->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-base-content/60">
                                    <i class='bx bx-calendar'></i>
                                    <span>{{ $voucher->tanggal_beli->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                            </div>

                            @if($voucher->status_pembayaran === 'pending' && $voucher->metode_pembayaran === 'cash')
                                <div class="alert alert-warning mt-4 py-2 shadow-sm">
                                    <i class='bx bx-time-five text-lg'></i>
                                    <span class="text-xs">Menunggu konfirmasi kasir</span>
                                </div>
                            @endif

                            @if($voucher->status_pembayaran === 'pending' && $voucher->metode_pembayaran === 'qris')
                                @if(!$voucher->qris_image)
                                    <button wire:click="openUploadModal({{ $voucher->id }})" class="btn btn-warning btn-sm md:btn-md w-full mt-4 gap-2 shadow-lg">
                                        <i class='bx bx-upload text-lg'></i>
                                        <span class="font-semibold">Upload Bukti Pembayaran</span>
                                    </button>
                                @else
                                    <div class="alert alert-info mt-4 py-2 shadow-sm">
                                        <i class='bx bx-info-circle text-lg'></i>
                                        <span class="text-xs">Menunggu approval admin</span>
                                    </div>
                                @endif
                            @endif

                            @if($voucher->status_pembayaran === 'paid' && $voucher->status === 'aktif')
                                <div class="alert alert-success mt-4">
                                    <i class='bx bx-info-circle text-lg'></i>
                                    <div class="text-sm">
                                        <p class="font-semibold">Voucher Aktif - Siap Digunakan!</p>
                                        <p class="text-xs mt-1">Tunjukkan kode voucher ini ke kasir/karyawan untuk menggunakan voucher</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <i class='bx bx-sad text-8xl text-base-content/20'></i>
                        <p class="text-xl text-base-content/60 mt-4 mb-2">Belum ada voucher</p>
                        <p class="text-sm text-base-content/50 mb-6">Beli voucher sekarang dan nikmati bermain PlayStation!</p>
                        <a href="{{ route('member.beli') }}" class="btn btn-primary btn-md gap-2 shadow-lg px-8">
                            <i class='bx bx-plus text-xl'></i>
                            <span class="font-semibold">Beli Voucher Sekarang</span>
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($vouchers->hasPages())
                <div class="mt-6">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Bukti Pembayaran Modal -->
    @if($uploadingVoucherId)
        <div class="modal modal-open">
            <div class="modal-box max-w-md">
                <h3 class="font-bold text-lg mb-4">
                    <i class='bx bx-upload text-warning'></i>
                    Upload Bukti Pembayaran
                </h3>
                
                <form wire:submit.prevent="uploadBuktiPembayaran">
                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">Pilih Foto Bukti Transfer</span>
                        </label>
                        <input type="file" wire:model="buktiPembayaran" accept="image/*" 
                            class="file-input file-input-bordered file-input-warning w-full" required>
                        @error('buktiPembayaran') 
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                        <label class="label">
                            <span class="label-text-alt">Format: JPG, PNG, JPEG (Max 2MB)</span>
                        </label>
                    </div>

                    @if($buktiPembayaran)
                        <div class="mb-4">
                            <p class="text-sm font-semibold mb-2">Preview:</p>
                            <img src="{{ $buktiPembayaran->temporaryUrl() }}" alt="Preview" class="w-full rounded-lg border-2 border-warning">
                        </div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <i class='bx bx-info-circle'></i>
                        <span class="text-xs">Pastikan bukti transfer jelas dan terbaca. Admin akan melakukan verifikasi.</span>
                    </div>

                    <div class="modal-action">
                        <button type="button" wire:click="cancelUpload" class="btn btn-ghost">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-warning" :disabled="!buktiPembayaran">
                            <i class='bx bx-upload'></i>
                            Upload
                        </button>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" wire:click="cancelUpload"></label>
        </div>
    @endif
</div>

<script>
    window.addEventListener('close-upload-modal', () => {
        // Modal will close automatically when uploadingVoucherId is set to null
    });
</script>
