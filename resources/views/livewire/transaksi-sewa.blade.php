<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Transaksi Penyewaan</h1>
            <p class="text-gray-600">Proses penyewaan PlayStation</p>
        </div>
        @if(!$show_transaksi_form)
            <button wire:click="mulaiTransaksi" class="btn btn-primary btn-lg gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Transaksi Baru
            </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if($show_transaksi_form)
        <div class="card bg-base-100 shadow-2xl mb-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-2xl">Form Penyewaan</h2>
                    <button wire:click="$set('show_transaksi_form', false)" class="btn btn-ghost btn-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="store" class="space-y-6">
                    <!-- Pilih Pelanggan -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-lg">1. Pilih Pelanggan</span>
                        </label>
                        <div class="relative mb-2">
                            <input type="text" wire:model.live="search_pelanggan" placeholder="Cari nama atau HP..." class="input input-bordered w-full pl-12">
                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-2xl text-base-content/50'></i>
                        </div>
                        <select wire:model="pelanggan_id" class="select select-bordered select-lg" size="4">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">
                                    {{ $pelanggan->nama }} - {{ $pelanggan->nomor_hp }}
                                    @if($pelanggan->isMemberActive())
                                        ⭐ Member ({{ $pelanggan->getDiskonMember() }}% off)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('pelanggan_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pilih PlayStation -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-lg">2. Pilih PlayStation</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @forelse($playStations as $ps)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="play_station_id" value="{{ $ps->id }}" class="radio radio-primary hidden">
                                    <div class="card bg-base-200 hover:bg-base-300 hover:shadow-xl transition-all {{ $play_station_id == $ps->id ? 'ring-2 ring-primary' : '' }}">
                                        <div class="card-body p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h3 class="font-bold text-lg">{{ $ps->kode_ps }}</h3>
                                                    <p class="text-sm">{{ $ps->nama_konsol }}</p>
                                                </div>
                                                <div class="badge badge-lg {{ $ps->tipe == 'PS5' ? 'badge-primary' : ($ps->tipe == 'PS4' ? 'badge-secondary' : 'badge-accent') }}">
                                                    {{ $ps->tipe }}
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="text-2xl font-bold text-primary">
                                                    Rp {{ number_format($ps->tarif->harga_per_jam ?? 0, 0, ',', '.') }}
                                                </div>
                                                <div class="text-xs text-gray-500">per jam</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-3 text-center py-8">
                                    <p class="text-gray-500">Tidak ada PlayStation tersedia</p>
                                </div>
                            @endforelse
                        </div>
                        @error('play_station_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Durasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-lg">3. Durasi Sewa (Jam)</span>
                        </label>
                        <input type="number" wire:model="durasi_jam" class="input input-bordered input-lg" placeholder="Berapa jam?" min="1">
                        @error('durasi_jam') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <button type="button" wire:click="$set('show_transaksi_form', false)" class="btn btn-ghost btn-lg gap-2 px-6">
                            <i class='bx bx-x text-xl'></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg gap-2 px-8">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-semibold">Proses Penyewaan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Daftar Transaksi Berlangsung -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-xl mb-4">Transaksi Sedang Berlangsung</h2>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>PlayStation</th>
                            <th>Mulai</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiBerlangsung as $transaksi)
                            <tr>
                                <td class="font-mono font-bold">{{ $transaksi->kode_transaksi }}</td>
                                <td>
                                    <div class="font-semibold">{{ $transaksi->pelanggan->nama }}</div>
                                    @if($transaksi->pelanggan->isMemberActive())
                                        <span class="badge badge-success badge-sm">Member</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $transaksi->playStation->kode_ps }}</div>
                                    <div class="badge badge-sm">{{ $transaksi->playStation->tipe }}</div>
                                </td>
                                <td>{{ $transaksi->waktu_mulai->format('d/m/Y H:i') }}</td>
                                <td><span class="badge badge-info">{{ $transaksi->durasi_jam }} jam</span></td>
                                <td class="font-bold text-primary">Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</td>
                                <td><span class="badge badge-warning">Berlangsung</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">Tidak ada transaksi berlangsung</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transaksiBerlangsung->links() }}
            </div>
        </div>
    </div>
</div>
