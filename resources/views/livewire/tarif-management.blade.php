<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Manajemen Tarif</h1>
        <p class="text-gray-600">Atur harga sewa dan denda per jam untuk setiap tipe PlayStation</p>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($tarifs as $tarif)
            <div class="card bg-gradient-to-br {{ $tarif->tipe_ps == 'PS5' ? 'from-primary to-secondary' : ($tarif->tipe_ps == 'PS4' ? 'from-secondary to-accent' : 'from-accent to-neutral') }} text-white shadow-2xl">
                <div class="card-body">
                    <h2 class="card-title text-3xl justify-center mb-4">
                        {{ $tarif->tipe_ps }}
                    </h2>

                    @if($editing_id === $tarif->id)
                        <!-- Edit Mode -->
                        <form wire:submit.prevent="update">
                            <div class="space-y-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-white font-semibold">Harga per Jam</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="bg-white text-gray-700 font-bold">Rp</span>
                                        <input type="number" wire:model="harga_per_jam" class="input input-bordered w-full text-gray-900 font-bold" min="0" step="1000" required>
                                    </div>
                                    @error('harga_per_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-white font-semibold">Denda per Jam</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="bg-white text-gray-700 font-bold">Rp</span>
                                        <input type="number" wire:model="denda_per_jam" class="input input-bordered w-full text-gray-900 font-bold" min="0" step="1000" required>
                                    </div>
                                    @error('denda_per_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="card-actions justify-center gap-2 mt-6">
                                    <button type="submit" class="btn btn-success btn-md gap-2 px-8">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="font-semibold">Simpan</span>
                                    </button>
                                    <button type="button" wire:click="cancelEdit" class="btn btn-ghost btn-md gap-2 px-6">
                                        <i class='bx bx-x text-lg'></i>
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <!-- View Mode -->
                        <div class="text-center space-y-4">
                            <div>
                                <div class="text-sm opacity-80">Harga Sewa</div>
                                <div class="text-4xl font-bold">
                                    Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}
                                </div>
                                <div class="text-sm opacity-80">per jam</div>
                            </div>

                            <div class="divider"></div>

                            <div>
                                <div class="text-sm opacity-80">Denda Keterlambatan</div>
                                <div class="text-2xl font-bold">
                                    Rp {{ number_format($tarif->denda_per_jam, 0, ',', '.') }}
                                </div>
                                <div class="text-sm opacity-80">per jam</div>
                            </div>

                            <div class="card-actions justify-center mt-6">
                                <button wire:click="edit({{ $tarif->id }})" class="btn btn-ghost border-white text-white hover:bg-white hover:text-gray-900 gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Tarif
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Card -->
    <div class="mt-8">
        <div class="alert alert-info shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h3 class="font-bold">Informasi Tarif</h3>
                <div class="text-sm">
                    <ul class="list-disc list-inside mt-2">
                        <li>Harga per jam berlaku untuk durasi sewa normal</li>
                        <li>Denda per jam akan dikenakan untuk keterlambatan pengembalian</li>
                        <li>Member akan mendapat diskon sesuai persentase yang tertera pada kartu member</li>
                        <li>Perubahan tarif akan langsung berlaku untuk transaksi baru</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
