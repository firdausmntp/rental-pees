<div class="min-h-screen bg-base-200 p-4 md:p-6">
    <!-- Header dengan gradient -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary via-accent to-secondary p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                        <i class='bx bxs-coupon text-5xl'></i>
                        Voucher Management
                    </h1>
                    <p class="text-white/90 text-lg">Kelola voucher member dengan mudah</p>
                </div>
                <button wire:click="openModal" class="btn btn-lg gap-2 bg-white text-primary hover:bg-base-100 border-0 shadow-xl px-8">
                    <i class='bx bx-plus-circle text-xl'></i>
                    <span class="font-bold">Buat Voucher</span>
                </button>
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Filter Section -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h3 class="card-title text-xl mb-4">
                <i class='bx bx-filter text-primary'></i>
                Filter & Cari
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Cari Voucher</span>
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live="search" placeholder="Kode voucher atau nama member..." 
                               class="input input-bordered w-full pl-12">
                        <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-2xl text-base-content/50'></i>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <select wire:model.live="statusFilter" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="terpakai">Terpakai</option>
                        <option value="expired">Expired</option>
                        <option value="pending">⚠️ Pending QRIS</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">&nbsp;</span>
                    </label>
                    <button wire:click="$set('search', '')" class="btn btn-outline btn-md gap-2 px-6">
                        <i class='bx bx-refresh text-xl'></i>
                        <span class="font-semibold">Reset Filter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vouchers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($vouchers as $voucher)
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-2 
                @if($voucher->status === 'aktif') border-success 
                @elseif($voucher->status === 'terpakai') border-info 
                @else border-error @endif">
                
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="flex flex-wrap justify-between items-start gap-2 mb-3">
                        <div class="flex flex-wrap gap-2">
                            <div class="badge badge-lg gap-2 
                                @if($voucher->status === 'aktif') badge-success 
                                @elseif($voucher->status === 'terpakai') badge-info 
                                @else badge-error @endif">
                                <i class='bx bxs-circle text-xs'></i>
                                <span class="whitespace-nowrap">{{ strtoupper($voucher->status) }}</span>
                            </div>
                            @if(isset($voucher->status_pembayaran) && $voucher->status_pembayaran === 'pending')
                                <div class="badge badge-lg badge-warning gap-2">
                                    <i class='bx bx-time-five text-xs'></i>
                                    <span class="whitespace-nowrap text-xs sm:text-sm">PENDING</span>
                                </div>
                            @endif
                        </div>
                        @if($voucher->status === 'aktif')
                            <button wire:click="confirmDelete({{ $voucher->id }})"
                                    class="btn btn-circle btn-sm btn-error btn-ghost">
                                <i class='bx bx-trash'></i>
                            </button>
                        @endif
                    </div>

                    <!-- Kode Voucher -->
                    <div class="bg-gradient-to-r from-primary to-accent rounded-xl p-4 mb-4 text-center">
                        <p class="text-white/70 text-xs mb-1">KODE VOUCHER</p>
                        <p class="text-white font-mono text-xl font-bold tracking-wider">{{ $voucher->kode_voucher }}</p>
                    </div>

                    <!-- Detail -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-user text-primary text-xl'></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                @if($voucher->member_id)
                                    <p class="text-xs text-base-content/60">Member</p>
                                    <p class="font-semibold truncate">{{ $voucher->member->name }}</p>
                                @else
                                    <p class="text-xs text-base-content/60">Pembeli</p>
                                    <p class="font-semibold truncate">{{ $voucher->nama_pembeli }}</p>
                                    <span class="badge badge-sm badge-ghost mt-1">Non-Member</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-time text-secondary text-xl'></i>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/60">Durasi</p>
                                <p class="font-semibold">{{ $voucher->durasi_jam }} Jam</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-money text-success text-xl'></i>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/60">Total Harga</p>
                                <p class="font-bold text-success text-lg">
                                    Rp {{ number_format($voucher->total_harga, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-base-content/50">Rp {{ number_format($voucher->harga_per_jam, 0, ',', '.') }}/jam × {{ $voucher->durasi_jam }} jam</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-purchase-tag text-accent text-xl'></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-base-content/60">Tarif</p>
                                <p class="font-semibold truncate">
                                    @if($voucher->tarif)
                                        {{ $voucher->tarif->tipe_ps }} - {{ $voucher->tarif->jenis_tarif ?? 'Tarif Standard' }}
                                    @elseif($voucher->harga_per_jam > 0)
                                        <span class="text-warning">Rp {{ number_format($voucher->harga_per_jam, 0, ',', '.') }}/jam</span>
                                    @else
                                        <span class="text-error">Tarif tidak tersedia</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="divider my-2"></div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-info/10 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-calendar text-info text-xl'></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-base-content/60">Tanggal Beli</p>
                                <p class="font-semibold text-sm">{{ $voucher->tanggal_beli->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if($voucher->status === 'terpakai')
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center">
                                    <i class='bx bx-check-circle text-success text-xl'></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-base-content/60">Dipakai Pada</p>
                                    <p class="font-semibold text-sm">{{ $voucher->tanggal_pakai->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center">
                                    <i class='bx bx-calendar-x text-warning text-xl'></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-base-content/60">Expired</p>
                                    <p class="font-semibold text-sm">{{ $voucher->expired_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Tombol Approve/Reject untuk QRIS Pending -->
                        @if(isset($voucher->status_pembayaran) && $voucher->status_pembayaran === 'pending')
                            <div class="divider my-2"></div>
                            <div class="alert alert-warning text-sm py-2">
                                <i class='bx bx-info-circle'></i>
                                <span class="text-xs">Menunggu konfirmasi pembayaran</span>
                            </div>
                            
                            @if($voucher->qris_image)
                                <div class="mt-3">
                                    <p class="text-xs text-base-content/60 mb-2">Bukti Pembayaran:</p>
                                    <div class="relative group cursor-pointer" wire:click="showImage('{{ $voucher->qris_image }}')">
                                        <img src="{{ asset('storage/' . $voucher->qris_image) }}" 
                                             alt="Bukti Pembayaran" 
                                             class="w-full h-32 object-cover rounded-lg border-2 border-base-300 hover:border-primary transition-all">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 rounded-lg transition-all flex items-center justify-center">
                                            <i class='bx bx-search-alt text-4xl text-white opacity-0 group-hover:opacity-100 transition-all'></i>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-3">
                                    <div class="alert alert-info text-xs py-2">
                                        <i class='bx bx-info-circle'></i>
                                        <span>Belum ada bukti pembayaran (Cash)</span>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex gap-2 mt-3">
                                <button wire:click="confirmApprove({{ $voucher->id }})"
                                        class="btn btn-success btn-sm md:btn-md flex-1 gap-2">
                                    <i class='bx bx-check text-lg'></i>
                                    <span class="font-semibold">Approve</span>
                                </button>
                                <button wire:click="confirmReject({{ $voucher->id }})"
                                        class="btn btn-error btn-sm md:btn-md flex-1 gap-2">
                                    <i class='bx bx-x text-lg'></i>
                                    <span class="font-semibold">Tolak</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center py-16">
                        <i class='bx bx-coupon text-8xl text-base-content/20'></i>
                        <h3 class="text-2xl font-bold mt-4">Belum Ada Voucher</h3>
                        <p class="text-base-content/60">Klik tombol "Buat Voucher" untuk membuat voucher baru</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $vouchers->links() }}
    </div>

    <!-- Modal Buat Voucher -->
    @if($isOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-2xl bg-gradient-to-br from-base-100 to-base-200">
                <h3 class="font-bold text-3xl mb-6 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center">
                        <i class='bx bx-plus text-white text-2xl'></i>
                    </div>
                    Buat Voucher Baru
                </h3>
                
                <form wire:submit.prevent="store">
                    <div class="space-y-4">
                        <!-- Pilihan: Member atau Custom Name -->
                        <div class="alert alert-info">
                            <i class='bx bx-info-circle text-xl'></i>
                            <span>Pilih member yang terdaftar ATAU isi nama pembeli manual</span>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Pilih Member (Opsional)</span>
                            </label>
                            <select wire:model.live="member_id" class="select select-bordered w-full">
                                <option value="">-- Pilih Member (atau isi nama di bawah) --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                                @endforeach
                            </select>
                            @error('member_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="divider">ATAU</div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Nama Pembeli (Jika tidak ada member)</span>
                            </label>
                            <input type="text" wire:model.live="nama_pembeli" 
                                   class="input input-bordered w-full" 
                                   placeholder="Contoh: Karyawan A, Pelanggan Walk-in, dll"
                                   @if($member_id) disabled @endif>
                            @error('nama_pembeli') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            @if($member_id)
                                <label class="label">
                                    <span class="label-text-alt text-warning">Member dipilih, nama custom dinonaktifkan</span>
                                </label>
                            @endif
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Metode Pembayaran</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="label cursor-pointer border-2 rounded-lg p-4 has-[:checked]:border-success has-[:checked]:bg-success/10">
                                    <div class="flex flex-col items-center gap-2 flex-1">
                                        <i class='bx bx-money text-3xl text-success'></i>
                                        <span class="label-text font-semibold">Cash</span>
                                    </div>
                                    <input type="radio" wire:model="metode_pembayaran" value="cash" class="radio radio-success" checked />
                                </label>
                                <label class="label cursor-pointer border-2 rounded-lg p-4 has-[:checked]:border-warning has-[:checked]:bg-warning/10">
                                    <div class="flex flex-col items-center gap-2 flex-1">
                                        <i class='bx bx-qr text-3xl text-warning'></i>
                                        <span class="label-text font-semibold">QRIS</span>
                                    </div>
                                    <input type="radio" wire:model="metode_pembayaran" value="qris" class="radio radio-warning" />
                                </label>
                            </div>
                            @error('metode_pembayaran') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Pilih Tarif</span>
                            </label>
                            <select wire:model="tarif_id" class="select select-bordered w-full">
                                <option value="">-- Pilih Tarif --</option>
                                @foreach($tarifs as $tarif)
                                    <option value="{{ $tarif->id }}">{{ $tarif->tipe_ps }} - {{ $tarif->jenis_tarif }} (Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}/jam)</option>
                                @endforeach
                            </select>
                            @error('tarif_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Durasi (Jam)</span>
                                </label>
                                <input type="number" wire:model="durasi_jam" class="input input-bordered" min="1" placeholder="Contoh: 5">
                                @error('durasi_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Masa Berlaku (Hari)</span>
                                </label>
                                <input type="number" wire:model="expired_days" class="input input-bordered" min="1" max="365" placeholder="Contoh: 30">
                                @error('expired_days') <span class="text-error text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="alert alert-success">
                            <i class='bx bx-info-circle text-xl'></i>
                            <div class="text-sm">
                                <p class="font-semibold">Kode voucher akan digenerate otomatis</p>
                                @if($metode_pembayaran === 'cash')
                                    <p class="text-xs mt-1">✓ Status: AKTIF & LUNAS (Cash)</p>
                                @else
                                    <p class="text-xs mt-1">⚠️ Status: PENDING (Menunggu bukti pembayaran QRIS)</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-action">
                        <button type="button" wire:click="closeModal" class="btn btn-ghost btn-md gap-2 px-6">
                            <i class='bx bx-x text-xl'></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary btn-md gap-2 px-8 bg-gradient-to-r from-purple-600 to-pink-600 border-0">
                            <i class='bx bx-save text-xl'></i>
                            <span class="font-semibold">Simpan Voucher</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-backdrop" wire:click="closeModal"></div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error mb-4">
                    <i class='bx bx-trash'></i>
                    Hapus Voucher
                </h3>
                <p class="py-4">Yakin ingin hapus voucher ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="modal-action">
                    <button wire:click="cancelConfirmation" class="btn btn-ghost">Batal</button>
                    <button wire:click="executeDelete" class="btn btn-error">
                        <i class='bx bx-trash'></i>
                        Hapus
                    </button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelConfirmation"></div>
        </div>
    @endif

    <!-- Approve Confirmation Modal -->
    @if($confirmingApprove)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-success mb-4">
                    <i class='bx bx-check-circle'></i>
                    Konfirmasi Pembayaran
                </h3>
                <p class="py-4">Konfirmasi pembayaran QRIS ini? Voucher akan diaktifkan.</p>
                <div class="modal-action">
                    <button wire:click="cancelConfirmation" class="btn btn-ghost">Batal</button>
                    <button wire:click="executeApprove" class="btn btn-success">
                        <i class='bx bx-check'></i>
                        Approve
                    </button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelConfirmation"></div>
        </div>
    @endif

    <!-- Reject Confirmation Modal -->
    @if($confirmingReject)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error mb-4">
                    <i class='bx bx-x-circle'></i>
                    Tolak Pembayaran
                </h3>
                <p class="py-4">Batalkan dan hapus voucher ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="modal-action">
                    <button wire:click="cancelConfirmation" class="btn btn-ghost">Batal</button>
                    <button wire:click="executeReject" class="btn btn-error">
                        <i class='bx bx-x'></i>
                        Tolak & Hapus
                    </button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelConfirmation"></div>
        </div>
    @endif

    <!-- Image Modal -->
    @if($showImageModal)
        <div class="modal modal-open">
            <div class="modal-box max-w-4xl bg-base-100">
                <button wire:click="closeImageModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
                    <i class='bx bx-x text-2xl'></i>
                </button>
                <h3 class="font-bold text-lg mb-4">
                    <i class='bx bx-image'></i>
                    Bukti Pembayaran
                </h3>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $selectedImage) }}" 
                         alt="Bukti Pembayaran" 
                         class="max-w-full max-h-[70vh] object-contain rounded-lg border-2 border-base-300">
                </div>
                <div class="modal-action">
                    <a href="{{ asset('storage/' . $selectedImage) }}" 
                       target="_blank" 
                       class="btn btn-primary gap-2">
                        <i class='bx bx-download'></i>
                        Buka di Tab Baru
                    </a>
                    <button wire:click="closeImageModal" class="btn btn-ghost">Tutup</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="closeImageModal"></div>
        </div>
    @endif
</div>
