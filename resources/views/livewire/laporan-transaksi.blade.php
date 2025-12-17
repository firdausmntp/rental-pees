<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Laporan Transaksi</h1>
        <p class="text-gray-600">Analisis dan statistik transaksi penyewaan PlayStation</p>
    </div>

    <!-- Filter Card -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Filter Laporan</h2>
                <button wire:click="exportExcel" class="btn btn-success gap-2">
                    <i class='bx bxs-file-export text-lg'></i>
                    Export Excel
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Tanggal Awal</span>
                    </label>
                    <input type="date" wire:model.live="tanggal_awal" class="input input-bordered bg-base-100">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Tanggal Akhir</span>
                    </label>
                    <input type="date" wire:model.live="tanggal_akhir" class="input input-bordered bg-base-100">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <select wire:model.live="status" class="select select-bordered bg-base-100">
                        <option value="">Semua Status</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Tipe PlayStation</span>
                    </label>
                    <select wire:model.live="tipe_ps" class="select select-bordered bg-base-100">
                        <option value="">Semua Tipe</option>
                        <option value="PS3">PS3</option>
                        <option value="PS4">PS4</option>
                        <option value="PS5">PS5</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="stat-title">Total Transaksi</div>
                <div class="stat-value text-primary">{{ number_format($total_transaksi, 0, ',', '.') }}</div>
                <div class="stat-desc">Sewa PS + Voucher dibeli</div>
            </div>
        </div>

        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-success">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-title">Total Pendapatan</div>
                <div class="stat-value text-success">Rp {{ number_format($total_pendapatan / 1000, 0, ',', '.') }}K</div>
                <div class="stat-desc">Sewa + Voucher dibeli</div>
            </div>
        </div>
    </div>

    <!-- PS Terpopuler & Pelanggan Teraktif -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- PS Terpopuler -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    PlayStation Terpopuler
                </h2>
                <div class="space-y-3 mt-4">
                    @forelse($ps_terpopuler as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-10 flex items-center justify-center">
                                        <span class="text-xl font-bold">#{{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $item->playStation->kode_ps }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->playStation->nama_konsol }}</div>
                                </div>
                                <span class="badge badge-sm">{{ $item->playStation->tipe }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-primary">{{ $item->total }}x</div>
                                <div class="text-xs text-gray-500">disewa</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class='bx bx-joystick text-5xl text-base-content/20 mb-3'></i>
                            <p class="text-sm text-base-content/50">Belum ada transaksi sewa</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pelanggan Teraktif -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pelanggan Teraktif
                </h2>
                <div class="space-y-3 mt-4">
                    @forelse($pelanggan_teraktif as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-secondary text-secondary-content rounded-full w-10 flex items-center justify-center">
                                        <span class="text-xl font-bold">#{{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ is_object($item->pelanggan) ? $item->pelanggan->nama : 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ is_object($item->pelanggan) ? $item->pelanggan->nomor_hp : 'N/A' }}</div>
                                </div>
                                @if(is_callable([$item->pelanggan, 'isMemberActive']) && $item->pelanggan->isMemberActive())
                                    <span class="badge badge-success badge-sm">⭐ Member</span>
                                @elseif(is_object($item->pelanggan) && isset($item->pelanggan->id) && str_starts_with($item->pelanggan->id, 'member_'))
                                    <span class="badge badge-success badge-sm">⭐ Member</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-secondary">{{ $item->total }}x</div>
                                <div class="text-xs text-primary">Rp {{ number_format($item->total_biaya / 1000, 0, ',', '.') }}K</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class='bx bx-user text-5xl text-base-content/20 mb-3'></i>
                            <p class="text-sm text-base-content/50">Belum ada transaksi pelanggan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Transaksi -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Detail Transaksi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>PlayStation</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                            <tr>
                                <td class="font-mono text-xs">{{ $transaksi->kode_transaksi }}</td>
                                <td class="text-sm">
                                    <div>{{ $transaksi->waktu_mulai->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaksi->waktu_mulai->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold">{{ $transaksi->pelanggan->nama }}</div>
                                    @if($transaksi->pelanggan->isMemberActive())
                                        <span class="badge badge-success badge-xs">Member</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-semibold">{{ $transaksi->playStation->kode_ps }}</div>
                                    <span class="badge badge-sm">{{ $transaksi->playStation->tipe }}</span>
                                </td>
                                <td>
                                    {{ $transaksi->durasi_jam }} jam
                                    @if($transaksi->durasi_aktual && $transaksi->durasi_aktual != $transaksi->durasi_jam)
                                        <div class="text-xs text-warning">({{ $transaksi->durasi_aktual }} jam)</div>
                                    @endif
                                </td>
                                <td class="font-bold">
                                    @if($transaksi->voucher)
                                        <div>Rp {{ number_format($transaksi->voucher->total_harga, 0, ',', '.') }}</div>
                                        <div class="badge badge-primary badge-xs mt-1">Voucher</div>
                                    @else
                                        Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif($transaksi->status == 'berlangsung')
                                        <span class="badge badge-warning">Berlangsung</span>
                                    @else
                                        <span class="badge badge-error">Batal</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ $transaksi->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class='bx bx-data text-6xl text-base-content/20 mb-4'></i>
                                        <p class="text-lg font-semibold text-base-content/60 mb-2">Tidak ada transaksi sewa PS</p>
                                        <p class="text-sm text-base-content/50 mb-4">Detail transaksi sewa walk-in akan muncul di sini</p>
                                        @if($total_transaksi > 0)
                                            <div class="alert alert-info max-w-md">
                                                <i class='bx bx-info-circle text-xl'></i>
                                                <div class="text-left text-sm">
                                                    <p class="font-semibold">Ada {{ $total_transaksi }} transaksi (voucher)</p>
                                                    <p>Total pendapatan: Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>

    <!-- Tabel Kompromi Vouchers -->
    @if(isset($kompromis) && $kompromis->count() > 0)
        <div class="card bg-base-100 shadow-xl mt-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title">
                        <i class='bx bx-handshake text-info text-2xl'></i>
                        Voucher Kompromi (Kompensasi)
                    </h2>
                    <span class="badge badge-info badge-lg">{{ $kompromis->total() }} Voucher</span>
                </div>
                <p class="text-sm text-base-content/60 mb-4">Voucher kompensasi dari stop transaksi - <strong>tidak dihitung ke omset</strong></p>

                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Kode Voucher</th>
                                <th>Pembeli</th>
                                <th>Durasi</th>
                                <th>PlayStation</th>
                                <th>Tanggal Dibuat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kompromis as $kompromi)
                                <tr>
                                    <td>
                                        <div class="font-mono font-bold">{{ $kompromi->kode_voucher }}</div>
                                        <span class="badge badge-info gap-1">
                                            <i class='bx bx-handshake text-xs'></i>
                                            KOMPROMI
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-semibold">{{ $kompromi->nama_pembeli ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if($kompromi->durasi_menit)
                                            <div class="font-semibold">{{ $kompromi->durasi_menit }} Menit</div>
                                            <div class="text-xs text-base-content/50">(≈ {{ $kompromi->durasi_jam }} jam)</div>
                                        @else
                                            {{ $kompromi->durasi_jam }} Jam
                                        @endif
                                    </td>
                                    <td>
                                        @if($kompromi->tarif)
                                            <div class="font-semibold">{{ $kompromi->tarif->tipe_ps }}</div>
                                            <div class="text-xs text-base-content/50">{{ $kompromi->tarif->jenis_tarif ?? 'Standard' }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-sm">
                                        <div>{{ $kompromi->tanggal_beli->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $kompromi->tanggal_beli->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        @if($kompromi->status == 'aktif')
                                            <span class="badge badge-success">Aktif</span>
                                        @elseif($kompromi->status == 'terpakai')
                                            <span class="badge badge-info">Terpakai</span>
                                        @else
                                            <span class="badge badge-error">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-base-content/60">
                                        Tidak ada voucher kompromi pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $kompromis->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

```
