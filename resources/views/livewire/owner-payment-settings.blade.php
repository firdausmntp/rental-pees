<div class="min-h-screen bg-base-200 p-6">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class='bx bx-check-circle text-2xl'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-4">
                <i class='bx bx-error-circle text-2xl'></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h1 class="card-title text-3xl flex items-center gap-3">
                    <i class='bx bx-credit-card-front text-primary text-4xl'></i>
                    Pengaturan Metode Pembayaran
                </h1>
                <p class="text-base-content/70 text-sm">
                    Aktif/nonaktifkan metode pembayaran yang tersedia untuk member. Jika metode dinonaktifkan, semua voucher pending tanpa bukti pembayaran akan otomatis dibatalkan.
                </p>
            </div>
        </div>

        <div class="grid gap-4">
            @foreach($states as $method => $state)
                <div class="card bg-base-100 shadow-lg border {{ $state['enabled'] ? 'border-success/40' : 'border-error/40' }}">
                    <div class="card-body flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-bold flex items-center gap-2">
                                <span>{{ $state['label'] }}</span>
                                <span class="badge {{ $state['enabled'] ? 'badge-success' : 'badge-error' }}">
                                    {{ $state['enabled'] ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </h2>
                            <p class="text-sm text-base-content/70 mt-1">{{ $state['description'] }}</p>
                            @if(!$state['can_toggle'])
                                <p class="text-xs text-error mt-2">Metode ini tidak dapat diubah dari panel karena belum diaktifkan pada konfigurasi server.</p>
                            @endif
                            @if($state['pending_without_proof'] > 0)
                                <p class="text-xs text-warning mt-2">
                                    {{ $state['pending_without_proof'] }} transaksi pending tanpa bukti.
                                </p>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 items-stretch md:items-end">
                            <button
                                class="btn {{ $state['enabled'] ? 'btn-error' : 'btn-success' }}"
                                wire:click="toggle('{{ $method }}')"
                                @if(!$state['can_toggle']) disabled @endif
                            >
                                {{ $state['enabled'] ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
