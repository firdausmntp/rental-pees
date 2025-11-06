<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">User Management</h1>
            <p class="text-gray-600">Kelola pengguna sistem (Owner, Karyawan, Member)</p>
        </div>
        @if($currentUser->isOwner() || $currentUser->isKaryawan())
            <button wire:click="openModal" class="btn btn-primary btn-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ $currentUser->isKaryawan() ? 'Tambah Member' : 'Tambah User' }}
            </button>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
                <div class="stat-title">Total Users</div>
                <div class="stat-value text-primary">{{ $users->total() }}</div>
            </div>
        </div>

        @if($currentUser->isOwner())
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-success">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="stat-title">Owner</div>
                <div class="stat-value text-success">{{ \App\Models\User::where('role', 'owner')->count() }}</div>
            </div>
        </div>

        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-info">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                </div>
                <div class="stat-title">Karyawan</div>
                <div class="stat-value text-info">{{ \App\Models\User::where('role', 'karyawan')->count() }}</div>
            </div>
        </div>
        @endif

        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-warning">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                </div>
                <div class="stat-title">Member</div>
                <div class="stat-value text-warning">{{ \App\Models\User::where('role', 'member')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex gap-4 mb-4">
                <div class="flex-1 relative">
                    <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="input input-bordered w-full pl-12">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-2xl text-base-content/50'></i>
                </div>
                @if($currentUser->isOwner())
                <div>
                    <select wire:model.live="roleFilter" class="select select-bordered">
                        <option value="">Semua Role</option>
                        <option value="owner">Owner</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="member">Member</option>
                    </select>
                </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            @if($currentUser->isOwner() || $currentUser->isKaryawan())
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-gradient-to-br from-primary to-secondary text-white rounded-full w-12 flex items-center justify-center">
                                                <span class="text-xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $user->name }}</div>
                                            @if($user->id === auth()->id())
                                                <span class="badge badge-sm badge-info">Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($currentUser->isKaryawan() && $user->role === 'owner')
                                        <span class="text-base-content/40">***@***.***</span>
                                    @else
                                        {{ $user->email }}
                                    @endif
                                </td>
                                <td>
                                    @if($user->role === 'owner')
                                        <span class="badge badge-success gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Owner
                                        </span>
                                    @elseif($user->role === 'karyawan')
                                        <span class="badge badge-info gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            Karyawan
                                        </span>
                                    @else
                                        <span class="badge badge-warning gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                            Member
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                @if($currentUser->isOwner() || $currentUser->isKaryawan())
                                <td>
                                    <div class="flex gap-2">
                                        @if($user->role !== 'owner' || $currentUser->isOwner())
                                        <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-info">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                            Edit
                                        </button>
                                        @if($user->id !== auth()->id())
                                        <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-sm btn-error">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            Hapus
                                        </button>
                                        @endif
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada user ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit User -->
    @if($isOpen)
    <div class="modal modal-open">
        <div class="modal-box max-w-2xl bg-gradient-to-br from-base-100 to-base-200">
            <button wire:click="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            
            <h3 class="font-bold text-3xl mb-6 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-primary to-secondary flex items-center justify-center">
                    <i class='bx {{ $isEdit ? "bx-edit" : "bx-user-plus" }} text-white text-2xl'></i>
                </div>
                {{ $isEdit ? 'Edit User' : ($currentUser->isKaryawan() ? 'Tambah Member' : 'Tambah User') }}
            </h3>
            
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold flex items-center gap-2">
                                <i class='bx bx-user text-primary'></i>
                                Nama Lengkap
                            </span>
                        </label>
                        <input type="text" wire:model="name" class="input input-bordered w-full" placeholder="Contoh: John Doe">
                        @error('name') <span class="text-error text-sm flex items-center gap-1 mt-1"><i class='bx bx-error-circle'></i>{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold flex items-center gap-2">
                                <i class='bx bx-envelope text-primary'></i>
                                Email
                            </span>
                        </label>
                        <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="email@example.com">
                        @error('email') <span class="text-error text-sm flex items-center gap-1 mt-1"><i class='bx bx-error-circle'></i>{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold flex items-center gap-2">
                                <i class='bx bx-lock text-primary'></i>
                                Password
                            </span>
                        </label>
                        <input type="password" wire:model="password" class="input input-bordered w-full" placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' }}">
                        @error('password') <span class="text-error text-sm flex items-center gap-1 mt-1"><i class='bx bx-error-circle'></i>{{ $message }}</span> @enderror
                        @if($isEdit)
                        <label class="label">
                            <span class="label-text-alt text-info">💡 Tip: Kosongkan jika tidak ingin mengubah password</span>
                        </label>
                        @endif
                    </div>

                    @if($currentUser->isOwner())
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold flex items-center gap-2">
                                <i class='bx bx-shield text-primary'></i>
                                Role / Hak Akses
                            </span>
                        </label>
                        <select wire:model="role" class="select select-bordered w-full">
                            <option value="member">👤 Member - Customer biasa</option>
                            <option value="karyawan">👨‍💼 Karyawan - Staff operasional</option>
                            <option value="owner">👑 Owner - Full akses</option>
                        </select>
                        @error('role') <span class="text-error text-sm flex items-center gap-1 mt-1"><i class='bx bx-error-circle'></i>{{ $message }}</span> @enderror
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class='bx bx-info-circle text-xl'></i>
                        <span>User baru akan otomatis terdaftar sebagai <strong>Member</strong></span>
                    </div>
                    @endif
                </div>

                <div class="modal-action mt-6">
                    <button type="button" wire:click="closeModal" class="btn btn-ghost btn-md gap-2 px-6">
                        <i class='bx bx-x text-xl'></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-md gap-2 px-8 bg-gradient-to-r from-primary to-secondary border-0">
                        <i class='bx {{ $isEdit ? "bx-save" : "bx-plus-circle" }} text-xl'></i>
                        <span class="font-semibold">{{ $isEdit ? 'Update User' : 'Simpan User' }}</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="modal-backdrop bg-black/50" wire:click="closeModal"></div>
    </div>
    @endif

    <!-- Modal Delete Confirmation -->
    @if($isDeleteOpen && $userToDelete)
    <div class="modal modal-open">
        <div class="modal-box border-2 border-error">
            <h3 class="font-bold text-2xl mb-4 flex items-center gap-3 text-error">
                <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center">
                    <i class='bx bx-error text-error text-3xl'></i>
                </div>
                Konfirmasi Hapus User
            </h3>
            
            <div class="py-4">
                <div class="alert alert-warning mb-4">
                    <i class='bx bx-info-circle text-xl'></i>
                    <span>Data yang dihapus tidak dapat dikembalikan!</span>
                </div>

                <p class="text-lg mb-2">Apakah Anda yakin ingin menghapus user:</p>
                <div class="bg-base-200 p-4 rounded-lg">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="avatar placeholder">
                            <div class="bg-error text-error-content rounded-full w-12 flex items-center justify-center">
                                <span class="text-xl font-bold">{{ strtoupper(substr($userToDelete->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ $userToDelete->name }}</p>
                            <p class="text-sm opacity-70">{{ $userToDelete->email }}</p>
                        </div>
                    </div>
                    <div class="badge badge-{{ $userToDelete->role === 'owner' ? 'success' : ($userToDelete->role === 'karyawan' ? 'info' : 'warning') }} gap-2">
                        @if($userToDelete->role === 'owner')
                            👑 Owner
                        @elseif($userToDelete->role === 'karyawan')
                            👨‍💼 Karyawan
                        @else
                            👤 Member
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button wire:click="cancelDelete" class="btn btn-ghost btn-md gap-2 px-6">
                    <i class='bx bx-x text-xl'></i>
                    Batal
                </button>
                <button wire:click="delete" class="btn btn-error btn-md gap-2 px-8">
                    <i class='bx bx-trash text-xl'></i>
                    <span class="font-semibold">Ya, Hapus User</span>
                </button>
            </div>
        </div>
        <div class="modal-backdrop bg-black/50" wire:click="cancelDelete"></div>
    </div>
    @endif
</div>
