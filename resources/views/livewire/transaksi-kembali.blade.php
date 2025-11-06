<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Transaksi Pengembalian</h1>
        <p class="text-gray-600">Proses pengembalian PlayStation dan perhitungan denda</p>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Form Pencarian -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl">Cari Transaksi Berlangsung</h2>
            <div class="form-control relative">
                <input type="text" wire:model.live="search" placeholder="Cari kode transaksi, nama pelanggan, atau PS..." class="input input-bordered input-lg pl-14">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-3xl text-base-content/50'></i>
            </div>
        </div>
    </div>

    <!-- Daftar Transaksi Berlangsung -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($transaksiBerlangsung as $transaksi)
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="card-title text-2xl font-mono">{{ $transaksi->kode_transaksi }}</h3>
                            <span class="badge badge-warning mt-2">Berlangsung</span>
                        </div>
                        <button wire:click="selectTransaksi({{ $transaksi->id }})" class="btn btn-primary btn-md gap-2 px-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="font-semibold">Proses</span>
                        </button>
                    </div>

                    <div class="divider"></div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500">Pelanggan</div>
                            <div class="font-bold">{{ $transaksi->pelanggan->nama }}</div>
                            @if($transaksi->pelanggan->isMemberActive())
                                <span class="badge badge-success badge-sm">⭐ Member</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">PlayStation</div>
                            <div class="font-bold">{{ $transaksi->playStation->kode_ps }}</div>
                            <span class="badge badge-sm">{{ $transaksi->playStation->tipe }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-sm text-gray-500">Waktu Mulai</div>
                        <div class="font-semibold">{{ $transaksi->waktu_mulai->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <div>
                            <div class="text-sm text-gray-500">Durasi Sewa</div>
                            <div class="badge badge-info badge-lg">{{ $transaksi->durasi_jam }} jam</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Total Awal</div>
                            <div class="font-bold text-primary">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 card bg-base-100 shadow-xl">
                <div class="card-body text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 mt-4">Tidak ada transaksi berlangsung</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal Pengembalian -->
    @if($selected_transaksi)
        <dialog id="kembali_modal" class="modal modal-open">
            <div class="modal-box max-w-2xl">
                <h3 class="font-bold text-2xl mb-4">Proses Pengembalian</h3>
                
                <div class="bg-base-200 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500">Kode Transaksi</div>
                            <div class="font-mono font-bold">{{ $selected_transaksi->kode_transaksi }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">PlayStation</div>
                            <div class="font-bold">{{ $selected_transaksi->playStation->kode_ps }} ({{ $selected_transaksi->playStation->tipe }})</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Pelanggan</div>
                            <div class="font-bold">{{ $selected_transaksi->pelanggan->nama }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Mulai Sewa</div>
                            <div>{{ $selected_transaksi->waktu_mulai->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="proses">
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-bold">Waktu Kembali</span>
                        </label>
                        <input type="datetime-local" wire:model="waktu_selesai" class="input input-bordered input-lg" required>
                        @error('waktu_selesai') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Perhitungan Biaya -->
                    @if($waktu_selesai)
                        <div class="card bg-primary text-primary-content mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Detail Pembayaran</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span>Durasi Sewa:</span>
                                        <span class="font-bold">{{ $selected_transaksi->durasi_jam }} jam</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Durasi Aktual:</span>
                                        <span class="font-bold">{{ $this->calculateActualDuration() }} jam</span>
                                    </div>
                                    @if($this->calculateDenda() > 0)
                                        <div class="divider my-1"></div>
                                        <div class="flex justify-between text-warning">
                                            <span>⚠️ Denda Keterlambatan:</span>
                                            <span class="font-bold">Rp {{ number_format($this->calculateDenda(), 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="divider my-1"></div>
                                    <div class="flex justify-between text-xl">
                                        <span class="font-bold">Total Akhir:</span>
                                        <span class="font-bold">Rp {{ number_format($this->calculateTotalAkhir(), 0, ',', '.') }}</span>
                                    </div>
                                    @if($selected_transaksi->pelanggan->isMemberActive())
                                        <div class="text-sm opacity-80">
                                            ⭐ Poin yang didapat: {{ floor($this->calculateTotalAkhir() / 10000) }} poin
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($this->calculateDenda() > 0)
                            <div class="alert alert-warning mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>Terdapat keterlambatan {{ ceil($this->calculateActualDuration() - $selected_transaksi->durasi_jam) }} jam!</span>
                            </div>
                        @endif
                    @endif

                    <div class="modal-action">
                        <button type="button" wire:click="closeModal" class="btn btn-ghost btn-lg gap-2 px-6">
                            <i class='bx bx-x text-xl'></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success btn-lg gap-2 px-8">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-semibold">Konfirmasi Pengembalian</span>
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button wire:click="closeModal">close</button>
            </form>
        </dialog>
    @endif

    <!-- Riwayat Transaksi Selesai -->
    <div class="mt-8">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-xl">Riwayat Transaksi Selesai (Hari Ini)</h2>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>PS</th>
                                <th>Durasi</th>
                                <th>Denda</th>
                                <th>Total</th>
                                <th>Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiSelesai as $transaksi)
                                <tr>
                                    <td class="font-mono text-xs">{{ $transaksi->kode_transaksi }}</td>
                                    <td>{{ $transaksi->pelanggan->nama }}</td>
                                    <td>
                                        <span class="badge badge-sm">{{ $transaksi->playStation->kode_ps }}</span>
                                    </td>
                                    <td>
                                        {{ $transaksi->durasi_jam }} / {{ $transaksi->durasi_aktual }} jam
                                    </td>
                                    <td>
                                        @if($transaksi->denda > 0)
                                            <span class="badge badge-warning">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="badge badge-ghost">-</span>
                                        @endif
                                    </td>
                                    <td class="font-bold">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</td>
                                    <td class="text-xs">{{ $transaksi->waktu_selesai?->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500">Belum ada transaksi selesai hari ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transaksiSelesai->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
