<div class="min-h-screen bg-base-200 p-4 md:p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold flex items-center gap-2">
                <i class='bx bx-pause-circle text-4xl text-primary'></i>
                Kompromi Waktu Main
            </h1>
            <p class="text-base-content/70">Stop sesi berjalan dan buat voucher sisa waktu otomatis.</p>
        </div>
    </div>

    @if($successMessage)
        <div class="alert alert-success shadow mb-4">
            <i class='bx bx-check-circle'></i>
            <span>{{ $successMessage }}</span>
        </div>
    @endif

    @if($errorMessage)
        <div class="alert alert-error shadow mb-4">
            <i class='bx bx-x-circle'></i>
            <span>{{ $errorMessage }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaksi</th>
                            <th>PS</th>
                            <th>Mulai</th>
                            <th>Durasi Awal</th>
                            <th>Berjalan</th>
                            <th>Sisa</th>
                            <th>Voucher (menit)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                            @php
                                $waktuMulai = \Carbon\Carbon::parse($trx->waktu_mulai);
                                $waktuSelesai = \Carbon\Carbon::parse($trx->waktu_selesai);
                                $startUnix = $waktuMulai->timestamp * 1000; // Convert to milliseconds
                                $endUnix = $waktuSelesai->timestamp * 1000; // Convert to milliseconds
                                // Calculate ACTUAL duration from waktu_selesai - waktu_mulai
                                $totalMinutes = (int) $waktuMulai->diffInMinutes($waktuSelesai);
                                $durasiDisplay = $totalMinutes >= 60 
                                    ? floor($totalMinutes / 60) . ' jam ' . ($totalMinutes % 60 > 0 ? ($totalMinutes % 60) . ' menit' : '')
                                    : $totalMinutes . ' menit';
                            @endphp
                            <tr x-data="transactionTimer({{ $startUnix }}, {{ $endUnix }}, {{ $totalMinutes }})">>
                                <td>
                                    <div class="font-semibold">{{ $trx->kode_transaksi }}</div>
                                    <div class="text-xs text-base-content/60">{{ $trx->pelanggan->nama ?? 'Walk-in' }}</div>
                                </td>
                                <td>{{ $trx->playStation->nama_konsol ?? $trx->playStation->tipe }}</td>
                                <td>{{ $waktuMulai->format('d M Y H:i') }}</td>
                                <td>{{ $durasiDisplay }}</td>
                                <td>
                                    <span x-text="elapsedMinutes"></span> menit
                                </td>
                                <td>
                                    <span x-text="remainingMinutes"></span> menit (≈ <span x-text="remainingHours"></span> jam)
                                </td>
                                <td>
                                    <div class="space-y-2" x-data="{ minutes: @json(isset($customMinutes[$trx->id]) && $customMinutes[$trx->id] > 0 ? (int) $customMinutes[$trx->id] : null) }">
                                        <div class="font-semibold text-lg">
                                            <span x-text="minutes !== null ? minutes : remainingMinutes"></span>
                                            <span>menit</span>
                                        </div>
                                        <input type="number" min="1" 
                                            wire:model.live="customMinutes.{{ $trx->id }}" 
                                            @input="minutes = parseInt($event.target.value) || null"
                                            :placeholder="remainingMinutes" 
                                            class="input input-bordered input-sm w-20 font-semibold text-center mt-1" />
                                        <div class="text-xs text-base-content/50">
                                            (sisa: <span x-text="remainingMinutes"></span> menit)
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button wire:click="stopAndGenerateVoucher({{ $trx->id }})" class="btn btn-primary btn-sm gap-2">
                                        <i class='bx bx-stop-circle text-lg'></i>
                                        Stop & Buat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-base-content/60 py-6">Tidak ada transaksi berjalan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function transactionTimer(startUnix, endUnix, totalMinutes) {
            return {
                elapsedMinutes: 0,
                remainingMinutes: totalMinutes,
                remainingHours: Math.max(1, Math.ceil(totalMinutes / 60)),
                
                init() {
                    this.updateTimer();
                    setInterval(() => this.updateTimer(), 1000);
                },
                
                updateTimer() {
                    const now = Date.now();
                    // Calculate remaining from endUnix (waktu_selesai)
                    const remainingMs = endUnix - now;
                    this.remainingMinutes = Math.max(0, Math.ceil(remainingMs / 1000 / 60));
                    this.elapsedMinutes = totalMinutes - this.remainingMinutes;
                    this.remainingHours = Math.max(1, Math.ceil(this.remainingMinutes / 60));
                }
            }
        }
    </script>
</div>
