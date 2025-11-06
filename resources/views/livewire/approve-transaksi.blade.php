<div class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 p-4 md:p-6">
    <!-- Header -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-green-600 via-emerald-600 to-teal-500 p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                        <i class='bx bx-check-double text-5xl animate-pulse'></i>
                        Approve Transaksi
                    </h1>
                    <p class="text-white/90 text-lg">Konfirmasi pembayaran voucher member</p>
                </div>
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success shadow-lg mb-6 animate-pulse">
            <i class='bx bx-check-circle text-2xl'></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error shadow-lg mb-6">
            <i class='bx bx-error-circle text-2xl'></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card bg-base-100 shadow-xl mb-6 border border-base-300">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <span class="label-text font-semibold">Filter Status</span>
                    </label>
                    <select wire:model.live="filter" class="select select-bordered w-full">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="all">Semua</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Vouchers List -->
    <div class="grid grid-cols-1 gap-4">
        @forelse ($vouchers as $voucher)
            <div class="card bg-gradient-to-br from-base-100 to-base-200 shadow-xl hover:shadow-2xl transition-all border-2 
                @if($voucher->status_pembayaran === 'pending') border-warning animate-pulse
                @elseif($voucher->status_pembayaran === 'paid') border-success
                @else border-error @endif">
                
                <div class="card-body">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Left: Voucher Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="badge badge-lg gap-2 
                                    @if($voucher->status_pembayaran === 'pending') badge-warning
                                    @elseif($voucher->status_pembayaran === 'paid') badge-success
                                    @else badge-error @endif">
                                    <i class='bx bxs-circle text-xs'></i>
                                    {{ strtoupper($voucher->status_pembayaran) }}
                                </div>
                                <div class="badge badge-outline">{{ strtoupper($voucher->metode_pembayaran) }}</div>
                            </div>

                            <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl p-4 mb-4 inline-block">
                                <p class="text-white/70 text-xs mb-1">KODE VOUCHER</p>
                                <p class="text-white font-mono text-2xl font-bold tracking-wider">{{ $voucher->kode_voucher }}</p>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-base-content/60">Member</p>
                                    <p class="font-bold">{{ $voucher->member->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60">Tarif</p>
                                    <p class="font-bold">{{ $voucher->tarif->jenis_tarif }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60">Durasi</p>
                                    <p class="font-bold">{{ $voucher->durasi_jam }} jam (+5 menit)</p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60">Total</p>
                                    <p class="font-bold text-primary">Rp {{ number_format($voucher->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="divider my-2"></div>

                            <div class="flex flex-wrap gap-4 text-sm">
                                <div>
                                    <i class='bx bx-calendar text-info'></i>
                                    <span class="text-base-content/60">Dibeli:</span>
                                    <span class="font-semibold">{{ $voucher->tanggal_beli->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($voucher->approved_at)
                                    <div>
                                        <i class='bx bx-check-circle text-success'></i>
                                        <span class="text-base-content/60">Approved:</span>
                                        <span class="font-semibold">{{ $voucher->approved_at->format('d/m/Y H:i') }}</span>
                                        <span class="text-xs">({{ $voucher->approvedBy->name }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        @if($voucher->status_pembayaran === 'pending')
                            <div class="flex md:flex-col gap-3 items-center justify-center md:w-48">
                                <button wire:click="approveVoucher({{ $voucher->id }})" 
                                        wire:confirm="Approve pembayaran voucher ini?"
                                        class="btn btn-success btn-lg gap-2 flex-1 md:w-full transform hover:scale-105 transition">
                                    <i class='bx bx-check-circle text-2xl'></i>
                                    <span class="font-bold">APPROVE</span>
                                </button>
                                <button wire:click="rejectVoucher({{ $voucher->id }})" 
                                        wire:confirm="Tolak voucher ini?"
                                        class="btn btn-error btn-outline gap-2 flex-1 md:w-full">
                                    <i class='bx bx-x-circle text-xl'></i>
                                    Tolak
                                </button>
                            </div>
                        @else
                            <div class="flex items-center justify-center md:w-48">
                                <div class="text-center">
                                    <i class='bx bx-check-shield text-6xl 
                                        @if($voucher->status_pembayaran === 'paid') text-success
                                        @else text-error @endif'></i>
                                    <p class="text-sm font-semibold mt-2">
                                        @if($voucher->status_pembayaran === 'paid') Sudah Approved
                                        @else Ditolak @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center py-16">
                    <i class='bx bx-folder-open text-8xl text-base-content/20'></i>
                    <h3 class="text-2xl font-bold mt-4">Tidak Ada Transaksi</h3>
                    <p class="text-base-content/60">Belum ada voucher yang perlu diapprove</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $vouchers->links() }}
    </div>
</div>
