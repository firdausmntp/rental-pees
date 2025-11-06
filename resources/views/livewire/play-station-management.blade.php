<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 flex items-center gap-3">
        <i class='bx bx-joystick-alt text-primary'></i>
        Manajemen PlayStation & Tarif
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-1 space-y-6">
            <!-- PlayStation Form -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">{{ $isEdit ? 'Edit' : 'Tambah' }} PlayStation</h2>
                    
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Kode PS</span></label>
                            <input type="text" wire:model="kode_ps" class="input input-bordered" placeholder="PS5-001">
                            @error('kode_ps') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="label"><span class="label-text">Tipe</span></label>
                            <select wire:model="tipe" class="select select-bordered">
                                <option value="">Pilih Tipe</option>
                                <option value="PS3">PS3</option>
                                <option value="PS4">PS4</option>
                                <option value="PS5">PS5</option>
                            </select>
                            @error('tipe') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="label"><span class="label-text">Nama Konsol</span></label>
                            <input type="text" wire:model="nama_konsol" class="input input-bordered" placeholder="PlayStation 5 Digital">
                            @error('nama_konsol') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="label"><span class="label-text">Status</span></label>
                            <select wire:model="status" class="select select-bordered">
                                <option value="">Pilih Status</option>
                                <option value="tersedia">Tersedia</option>
                                <option value="dipakai">Dipakai</option>
                                <option value="rusak">Rusak</option>
                            </select>
                            @error('status') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="label"><span class="label-text">Keterangan</span></label>
                            <textarea wire:model="keterangan" class="textarea textarea-bordered" placeholder="Opsional"></textarea>
                        </div>

                        <div class="card-actions justify-end mt-6">
                            @if($isEdit)
                                <button type="button" wire:click="resetForm" class="btn btn-ghost btn-md gap-2 px-6">
                                    <i class='bx bx-x text-lg'></i>
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary btn-md gap-2 px-8">
                                    <i class='bx bx-save text-lg'></i>
                                    <span class="font-semibold">Update</span>
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary btn-md gap-2 px-8">
                                    <i class='bx bx-plus-circle text-lg'></i>
                                    <span class="font-semibold">Simpan</span>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tarif Info Card -->
            <div class="card bg-gradient-to-br from-primary/10 to-accent/10 border-2 border-primary/20 shadow-xl">
                <div class="card-body">
                    <h3 class="font-bold text-xl flex items-center gap-2">
                        <i class='bx bx-purchase-tag text-primary'></i>
                        Tarif Sewa
                    </h3>
                    @foreach($tarifs as $tarif)
                        <div class="flex justify-between items-center py-3 border-b border-base-300 last:border-0">
                            <div>
                                <span class="font-bold text-lg badge badge-{{ 
                                    $tarif->tipe_ps == 'PS3' ? 'info' : 
                                    ($tarif->tipe_ps == 'PS4' ? 'warning' : 'error')
                                }} badge-lg">{{ $tarif->tipe_ps }}</span>
                            </div>
                            @if($isEditTarif && $editTarifId == $tarif->id)
                                <div class="flex gap-2 items-center">
                                    <input type="number" wire:model="editTarifHarga" class="input input-bordered input-sm w-32" />
                                    <button wire:click="updateTarif" class="btn btn-success btn-sm btn-circle">
                                        <i class='bx bx-check text-lg'></i>
                                    </button>
                                    <button wire:click="cancelEditTarif" class="btn btn-error btn-sm btn-circle">
                                        <i class='bx bx-x text-lg'></i>
                                    </button>
                                </div>
                            @else
                                <div class="flex gap-2 items-center">
                                    <span class="font-mono text-lg">Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}/jam</span>
                                    <button wire:click="editTarif({{ $tarif->id }})" class="btn btn-ghost btn-sm btn-circle">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="alert alert-info mt-4">
                        <i class='bx bx-info-circle'></i>
                        <span class="text-sm">Klik icon edit untuk mengubah harga tarif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <!-- Search & Filter -->
                    <div class="flex gap-4 mb-4">
                        <div class="relative flex-1">
                            <input type="text" wire:model.live="search" placeholder="Cari kode atau nama..." class="input input-bordered w-full pl-12">
                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-2xl text-base-content/50'></i>
                        </div>
                        <select wire:model.live="filterStatus" class="select select-bordered">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipakai">Dipakai</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tipe</th>
                                    <th>Nama Konsol</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($playstations as $ps)
                                    <tr>
                                        <td class="font-mono font-bold">{{ $ps->kode_ps }}</td>
                                        <td><span class="badge badge-neutral">{{ $ps->tipe }}</span></td>
                                        <td>{{ $ps->nama_konsol }}</td>
                                        <td>
                                            @if($ps->status == 'tersedia')
                                                <span class="badge badge-success">Tersedia</span>
                                            @elseif($ps->status == 'dipakai')
                                                <span class="badge badge-warning">Dipakai</span>
                                            @else
                                                <span class="badge badge-error">Rusak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <button wire:click="edit({{ $ps->id }})" class="btn btn-sm md:btn-md btn-info gap-2">
                                                    <i class='bx bx-edit text-lg'></i>
                                                    <span class="hidden md:inline">Edit</span>
                                                </button>
                                                <button wire:click="delete({{ $ps->id }})" wire:confirm="Yakin hapus data ini?" class="btn btn-sm md:btn-md btn-error gap-2">
                                                    <i class='bx bx-trash text-lg'></i>
                                                    <span class="hidden md:inline">Hapus</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $playstations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
