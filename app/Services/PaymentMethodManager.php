<?php

namespace App\Services;

use App\Models\PaymentSetting;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;

class PaymentMethodManager
{
    /**
     * @var array<string, array{label:string,description:string}>
     */
    protected array $definitions = [
        'cash' => [
            'label' => 'Cash',
            'description' => 'Pembayaran langsung di kasir.',
        ],
        'qris' => [
            'label' => 'QRIS Manual',
            'description' => 'Transfer QRIS dengan bukti upload manual.',
        ],
        'pakasir' => [
            'label' => 'Pakasir',
            'description' => 'Gateway otomatis via Pakasir (QRIS, e-Wallet, VA).',
        ],
        'midtrans' => [
            'label' => 'Midtrans',
            'description' => 'Gateway otomatis via Midtrans.',
        ],
    ];

    public function definitions(): array
    {
        return $this->definitions;
    }

    public function isEnabled(string $method): bool
    {
        if (! isset($this->definitions[$method])) {
            return false;
        }

        if (! $this->isBaseAvailable($method)) {
            return false;
        }

        $setting = PaymentSetting::firstWhere('method', $method);

        return $setting?->enabled ?? true;
    }

    public function getStates(): array
    {
        $states = [];
        foreach ($this->definitions as $method => $definition) {
            $states[$method] = array_merge($definition, [
                'enabled' => $this->isEnabled($method),
                'can_toggle' => $this->isBaseAvailable($method),
                'pending_without_proof' => $this->pendingWithoutProofCount($method),
            ]);
        }

        return $states;
    }

    public function availableMethods(): array
    {
        return collect(array_keys($this->definitions))
            ->filter(fn (string $method) => $this->isEnabled($method))
            ->values()
            ->all();
    }

    public function toggle(string $method, bool $enabled): int
    {
        if (! isset($this->definitions[$method])) {
            throw new \InvalidArgumentException("Metode {$method} tidak dikenali");
        }

        PaymentSetting::updateOrCreate(
            ['method' => $method],
            [
                'label' => $this->definitions[$method]['label'],
                'enabled' => $enabled,
            ]
        );

        if (! $enabled) {
            return $this->autoCancelPending($method);
        }

        return 0;
    }

    public function pendingWithoutProofCount(string $method): int
    {
        return $this->pendingQuery($method)->count();
    }

    protected function autoCancelPending(string $method): int
    {
        $query = $this->pendingQuery($method);
        $affected = $query->update([
            'status' => 'cancelled',
            'status_pembayaran' => 'cancelled',
        ]);

        if ($affected > 0) {
            Log::info('Pending vouchers were auto-cancelled after gateway disabled', [
                'method' => $method,
                'count' => $affected,
            ]);
        }

        return $affected;
    }

    protected function pendingQuery(string $method)
    {
        return Voucher::query()
            ->where('metode_pembayaran', $method)
            ->where('status_pembayaran', 'pending')
            ->whereNull('qris_image');
    }

    protected function isBaseAvailable(string $method): bool
    {
        return match ($method) {
            'pakasir' => (bool) config('services.pakasir.enabled'),
            'midtrans' => (bool) config('services.midtrans.enabled'),
            default => true,
        };
    }
}
