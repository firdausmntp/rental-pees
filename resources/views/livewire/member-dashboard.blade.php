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

    @if(session('info'))
        <div class="alert alert-info mb-6 shadow-lg">
            <i class='bx bx-info-circle text-2xl'></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Hero Header -->
    <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary via-accent to-primary/80 p-6 md:p-8 shadow-xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                        <i class='bx bxs-dashboard text-4xl md:text-5xl drop-shadow-lg'></i>
                        Dashboard Member
                    </h1>
                    <p class="text-white/95 text-base md:text-lg">Selamat datang kembali, <span class="font-semibold">{{ auth()->user()->name }}</span>!</p>
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
            <div class="flex flex-wrap gap-2 mb-6 justify-center md:justify-start">
                <button wire:click="$set('filterStatus', 'all')" 
                    class="btn btn-sm md:btn-md rounded-full px-6 {{ $filterStatus === 'all' ? 'btn-primary text-black dark:text-white' : 'btn-ghost bg-base-200' }}">
                    Semua
                </button>
                <button wire:click="$set('filterStatus', 'aktif')" 
                    class="btn btn-sm md:btn-md rounded-full px-6 {{ $filterStatus === 'aktif' ? 'btn-success text-black dark:text-white' : 'btn-ghost bg-base-200' }}">
                    Aktif
                </button>
                <button wire:click="$set('filterStatus', 'pending')" 
                    class="btn btn-sm md:btn-md rounded-full px-6 {{ $filterStatus === 'pending' ? 'btn-warning text-black dark:text-white' : 'btn-ghost bg-base-200' }}">
                    Pending
                </button>
                <button wire:click="$set('filterStatus', 'terpakai')" 
                    class="btn btn-sm md:btn-md rounded-full px-6 {{ $filterStatus === 'terpakai' ? 'btn-success text-black dark:text-white' : 'btn-ghost bg-base-200' }}">
                    Riwayat
                </button>
            </div>

            <!-- Voucher List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($vouchers as $voucher)
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 border border-base-200 group overflow-hidden">
                        <!-- Status Badge -->
                        <div class="absolute top-0 right-0 p-0 z-10">
                            @if($voucher->status_pembayaran === 'pending')
                                <div class="bg-warning text-warning-content px-4 py-1 rounded-bl-xl font-bold text-sm shadow-sm">
                                    <i class='bx bx-time-five'></i> Menunggu Pembayaran
                                </div>
                            @elseif($voucher->status === 'aktif')
                                <div class="bg-success text-success-content px-4 py-1 rounded-bl-xl font-bold text-sm shadow-sm">
                                    <i class='bx bx-check-circle'></i> Aktif
                                </div>
                            @else
                                <div class="bg-base-300 text-base-content px-4 py-1 rounded-bl-xl font-bold text-sm shadow-sm">
                                    {{ ucfirst($voucher->status) }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body p-0">
                            <!-- Card Header -->
                            <div class="p-6 {{ $voucher->status === 'aktif' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-base-200' }}">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-bold text-lg opacity-90">PlayStation {{ $voucher->tarif->tipe_ps ?? 'PS' }}</h3>
                                        <div class="text-3xl font-bold mt-1">
                                            @if($voucher->metode_pembayaran === 'kompromi' && $voucher->durasi_menit)
                                                {{ $voucher->durasi_menit }} Menit
                                                <span class="text-sm">(≈ {{ $voucher->durasi_jam }} jam)</span>
                                            @else
                                                {{ $voucher->durasi_jam }} Jam
                                            @endif
                                        </div>
                                        @if($voucher->metode_pembayaran === 'kompromi')
                                            <span class="badge badge-info badge-sm gap-1 mt-2">
                                                <i class='bx bx-handshake'></i>
                                                Kompromi
                                            </span>
                                        @endif
                                    </div>
                                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                                        <i class='bx bx-joystick text-2xl'></i>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-2 text-sm opacity-80">
                                    <i class='bx bx-calendar'></i>
                                    {{ $voucher->created_at->format('d M Y H:i') }}
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-6 space-y-4">
                                <!-- Kode Voucher Section -->
                                @if($voucher->status === 'aktif' && $voucher->kode_voucher)
                                    <div class="bg-base-200 p-4 rounded-xl text-center border-2 border-dashed border-primary/30 group-hover:border-primary transition-colors">
                                        <p class="text-xs text-base-content/60 mb-1">KODE VOUCHER</p>
                                        <p class="text-2xl font-mono font-bold text-primary tracking-wider select-all">{{ $voucher->kode_voucher }}</p>
                                    </div>
                                @elseif($voucher->status_pembayaran === 'pending')
                                    <div class="bg-warning/10 p-4 rounded-xl border border-warning/20">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm text-base-content/70">Total Tagihan</span>
                                            <span class="font-bold text-lg">Rp {{ number_format($voucher->qris_nominal ?? $voucher->total_harga, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        @if($voucher->metode_pembayaran === 'qris')
                                            <div class="text-xs text-base-content/60 mb-3">
                                                Transfer sesuai nominal unik (termasuk 3 digit terakhir) untuk verifikasi otomatis.
                                            </div>
                                            
                                            <div class="flex flex-col gap-2">
                                                <button wire:click="showQris({{ $voucher->id }})" class="btn btn-info btn-sm w-full gap-2 shadow-sm text-black dark:text-white">
                                                    <i class='bx bx-qr'></i> Lihat QRIS
                                                </button>

                                                @if(!$voucher->qris_image)
                                                    <button wire:click="openUploadModal({{ $voucher->id }})" class="btn btn-warning btn-sm w-full gap-2 shadow-sm">
                                                        <i class='bx bx-upload'></i> Upload Bukti
                                                    </button>
                                                @else
                                                    <div class="flex flex-col gap-2">
                                                        <div class="alert alert-success py-2 px-3 text-xs shadow-sm flex justify-between items-center">
                                                            <div class="flex items-center gap-2">
                                                                <i class='bx bx-check-circle'></i> Bukti terupload
                                                            </div>
                                                            <!-- Button to open modal -->
                                                            <button onclick="document.getElementById('preview_modal_{{ $voucher->id }}').showModal()" class="btn btn-xs btn-ghost btn-circle" type="button">
                                                                <i class='bx bx-show text-lg'></i>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Modal Preview (Native HTML dialog) -->
                                                        <dialog id="preview_modal_{{ $voucher->id }}" class="modal">
                                                            <div class="modal-box p-0 overflow-hidden max-w-sm">
                                                                <div class="bg-base-200 p-4 flex justify-between items-center">
                                                                    <h3 class="font-bold text-lg">Bukti Pembayaran</h3>
                                                                    <form method="dialog">
                                                                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                                                                    </form>
                                                                </div>
                                                                <div class="p-4 bg-base-100 flex justify-center">
                                                                    <img src="{{ asset('storage/' . $voucher->qris_image) }}" class="max-h-[60vh] rounded-lg shadow-md" alt="Bukti Transfer">
                                                                </div>
                                                                <form method="dialog" class="modal-backdrop">
                                                                    <button>close</button>
                                                                </form>
                                                            </div>
                                                        </dialog>
    
                                                        <button wire:click="openUploadModal({{ $voucher->id }})" class="btn btn-outline btn-warning btn-xs w-full gap-1">
                                                            <i class='bx bx-edit'></i> Ganti Bukti
                                                        </button>
                                                    </div>
                                                @endif

                                                <button wire:click="confirmCancel({{ $voucher->id }})" 
                                                    class="btn btn-ghost btn-xs w-full gap-1 text-error hover:bg-error/10">
                                                    <i class='bx bx-x-circle'></i> Batalkan
                                                </button>
                                            </div>
                                        @elseif($voucher->metode_pembayaran === 'pakasir')
                                            <div class="text-xs text-base-content/60 mb-3">
                                                Pembayaran otomatis melalui Pakasir. Klik tombol di bawah untuk membuka halaman pembayaran.
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <button wire:click="payPakasir({{ $voucher->id }})" class="btn btn-primary btn-sm w-full gap-2 shadow-sm">
                                                    <i class='bx bx-credit-card'></i> Bayar via Pakasir
                                                </button>
                                                <button wire:click="checkPakasirStatus({{ $voucher->id }})" class="btn btn-ghost btn-xs w-full gap-1">
                                                    <i class='bx bx-refresh'></i> Cek Status Pembayaran
                                                </button>
                                                <button wire:click="confirmCancel({{ $voucher->id }})" 
                                                    class="btn btn-ghost btn-xs w-full gap-1 text-error hover:bg-error/10">
                                                    <i class='bx bx-x-circle'></i> Batalkan
                                                </button>
                                            </div>
                                        @elseif($voucher->metode_pembayaran === 'midtrans')
                                            <div class="text-xs text-base-content/60 mb-3">
                                                Pembayaran otomatis melalui Midtrans. Klik tombol di bawah untuk membuka halaman pembayaran.
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <button wire:click="payMidtrans({{ $voucher->id }})" class="btn btn-primary btn-sm w-full gap-2 shadow-sm">
                                                    <i class='bx bx-credit-card'></i> Bayar via Midtrans
                                                </button>
                                                <button wire:click="checkMidtransStatus({{ $voucher->id }})" class="btn btn-ghost btn-xs w-full gap-1">
                                                    <i class='bx bx-refresh'></i> Cek Status Pembayaran
                                                </button>
                                                <button wire:click="confirmCancel({{ $voucher->id }})" 
                                                    class="btn btn-ghost btn-xs w-full gap-1 text-error hover:bg-error/10">
                                                    <i class='bx bx-x-circle'></i> Batalkan
                                                </button>
                                            </div>
                                        @else
                                            <div class="alert alert-info py-2 px-3 text-xs">
                                                <i class='bx bx-store'></i> Bayar di kasir
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Details -->
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between pb-2 border-b border-base-200">
                                        <span class="text-base-content/60">Harga Paket</span>
                                        <span class="font-semibold">Rp {{ number_format($voucher->harga_per_jam, 0, ',', '.') }}/jam</span>
                                    </div>
                                    <div class="flex justify-between pb-2 border-b border-base-200">
                                        <span class="text-base-content/60">Metode Bayar</span>
                                        <span class="badge badge-ghost font-semibold uppercase text-xs">{{ $voucher->metode_pembayaran }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-base-100 rounded-2xl border-2 border-dashed border-base-300">
                        <div class="w-24 h-24 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class='bx bx-joystick text-5xl text-base-content/30'></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Belum Ada Voucher</h3>
                        <p class="text-base-content/60 mb-6 max-w-md mx-auto">Kamu belum memiliki riwayat pembelian voucher. Yuk beli voucher sekarang dan mulai bermain!</p>
                        <a href="{{ route('member.beli') }}" class="btn btn-primary btn-lg gap-2 shadow-lg hover:scale-105 transition-transform">
                            <i class='bx bx-plus text-xl'></i>
                            Beli Voucher Baru
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
                        <button type="button" wire:click="closeUploadModal" class="btn btn-ghost">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                            <i class='bx bx-upload'></i>
                            Upload
                        </button>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" wire:click="closeUploadModal"></label>
        </div>
    @endif

    <!-- QRIS Modal -->
    @if($showQrisModal)
        <div class="modal modal-open">
            <div class="modal-box max-w-2xl">
                <h3 class="font-bold text-2xl mb-4 flex items-center gap-2">
                    <i class='bx bx-qr text-info text-3xl'></i>
                    Pembayaran QRIS
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
                                    -
                                @endif
                            </p>
                            <p class="text-xs text-base-content/60 mt-2">Nominal sudah termasuk kode unik. Mohon transfer sesuai angka yang tertera.</p>
                        </div>
                        <div class="alert alert-info text-xs">
                            <i class='bx bx-info-circle'></i>
                            <span>Setelah transfer, silakan upload bukti pembayaran di dashboard.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-primary" wire:click="closeQrisModal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Confirmation Modal -->
    @if($cancelVoucherId)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error flex items-center gap-2">
                    <i class='bx bx-error-circle text-2xl'></i>
                    Konfirmasi Pembatalan
                </h3>
                <p class="py-4">Apakah Anda yakin ingin membatalkan transaksi ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" wire:click="closeCancelModal">Tidak, Kembali</button>
                    <button type="button" class="btn btn-error" wire:click="cancelTransaction" wire:loading.attr="disabled">
                        <span wire:loading.remove>Ya, Batalkan Transaksi</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    window.addEventListener('close-upload-modal', () => {
        // Modal will close automatically when uploadingVoucherId is set to null
    });
</script>
