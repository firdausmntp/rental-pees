<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Data Pelanggan</h1>
        <button onclick="pelanggan_modal.showModal()" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pelanggan
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="Cari nama atau nomor HP..." class="input input-bordered w-full max-w-xs">
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nomor HP</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $pelanggan)
                            <tr>
                                <td class="font-semibold">{{ $pelanggan->nama }}</td>
                                <td class="font-mono">{{ $pelanggan->nomor_hp }}</td>
                                <td>{{ Str::limit($pelanggan->alamat, 30) }}</td>
                                <td>
                                    @if($pelanggan->isMemberActive())
                                        <span class="badge badge-success gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Member
                                        </span>
                                    @else
                                        <span class="badge badge-ghost">Reguler</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="edit({{ $pelanggan->id }})" onclick="pelanggan_modal.showModal()" class="btn btn-sm btn-info">Edit</button>
                                    <button wire:click="delete({{ $pelanggan->id }})" wire:confirm="Yakin hapus data ini?" class="btn btn-sm btn-error">Hapus</button>
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

            <div class="mt-4">
                {{ $pelanggans->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="pelanggan_modal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Pelanggan</h3>
            
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="form-control">
                    <label class="label"><span class="label-text">Nama Lengkap</span></label>
                    <input type="text" wire:model="nama" class="input input-bordered" placeholder="Nama pelanggan">
                    @error('nama') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label"><span class="label-text">Nomor HP</span></label>
                    <input type="text" wire:model="nomor_hp" class="input input-bordered" placeholder="08xxxxxxxxxx">
                    @error('nomor_hp') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label"><span class="label-text">Alamat</span></label>
                    <textarea wire:model="alamat" class="textarea textarea-bordered" placeholder="Alamat lengkap" rows="3"></textarea>
                    @error('alamat') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="pelanggan_modal.close(); @this.call('resetForm')">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="pelanggan_modal.close()">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button wire:click="resetForm">close</button>
        </form>
    </dialog>
</div>
