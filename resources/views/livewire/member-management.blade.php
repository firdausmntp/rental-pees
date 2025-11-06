<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Manajemen Member</h1>
            <p class="text-gray-600">Kelola member dan benefit mereka</p>
        </div>
        <button onclick="member_modal.showModal()" class="btn btn-primary btn-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Daftarkan Member
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
                <div class="stat-title">Total Member</div>
                <div class="stat-value text-primary">{{ $members->total() }}</div>
            </div>
        </div>

        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-success">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="stat-title">Member Aktif</div>
                <div class="stat-value text-success">{{ $members->where('is_active', true)->count() }}</div>
            </div>
        </div>

        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-warning">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div class="stat-title">Non Member</div>
                <div class="stat-value text-warning">{{ $pelangganNonMember->count() }}</div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="Cari kode member atau nama..." class="input input-bordered w-full max-w-xs">
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Member</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Daftar</th>
                            <th>Berlaku Hingga</th>
                            <th>Diskon</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="font-mono font-bold">{{ $member->kode_member }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-12">
                                                <span class="text-xl">{{ strtoupper(substr($member->pelanggan->nama, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $member->pelanggan->nama }}</div>
                                            <div class="text-sm opacity-50">{{ $member->pelanggan->nomor_hp }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $member->tanggal_daftar->format('d M Y') }}</td>
                                <td>
                                    {{ $member->tanggal_berakhir->format('d M Y') }}
                                    @if($member->isExpired())
                                        <span class="badge badge-error badge-sm">Expired</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-success">{{ $member->diskon_persen }}%</span></td>
                                <td>
                                    <div class="badge badge-primary badge-lg">{{ $member->poin }} pts</div>
                                </td>
                                <td>
                                    @if($member->is_active)
                                        <span class="badge badge-success gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-error">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="toggleStatus({{ $member->id }})" class="btn btn-sm btn-{{ $member->is_active ? 'warning' : 'success' }}">
                                        {{ $member->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    <button wire:click="delete({{ $member->id }})" wire:confirm="Yakin hapus member ini?" class="btn btn-sm btn-error">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada member terdaftar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $members->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Daftar Member -->
    <dialog id="member_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Daftarkan Member Baru</h3>
            
            <form wire:submit.prevent="store">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Pilih Pelanggan</span></label>
                    <select wire:model="pelanggan_id" class="select select-bordered">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelangganNonMember as $pelanggan)
                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }} - {{ $pelanggan->nomor_hp }}</option>
                        @endforeach
                    </select>
                    @error('pelanggan_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tanggal Daftar</span></label>
                        <input type="date" wire:model="tanggal_daftar" class="input input-bordered">
                        @error('tanggal_daftar') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Berlaku Hingga</span></label>
                        <input type="date" wire:model="tanggal_berakhir" class="input input-bordered">
                        @error('tanggal_berakhir') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label"><span class="label-text">Diskon (%)</span></label>
                    <input type="number" wire:model="diskon_persen" class="input input-bordered" min="0" max="100">
                    @error('diskon_persen') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="alert alert-info mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Kode member akan di-generate otomatis setelah disimpan</span>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="member_modal.close(); @this.call('resetForm')">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="member_modal.close()">Daftarkan</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button wire:click="resetForm">close</button>
        </form>
    </dialog>
</div>
