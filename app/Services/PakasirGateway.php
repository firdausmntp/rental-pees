<?php

namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class PakasirGateway
{
    public function enabled(): bool
    {
        return (bool) Config::get('services.pakasir.enabled', false);
    }

    public function buildPaymentUrl(Voucher $voucher, array $options = []): string
    {
        if (! $this->enabled()) {
            throw new RuntimeException('Pakasir gateway is disabled.');
        }

        $slug = $this->projectSlug();
        $amount = $this->normalizeAmount($voucher->total_harga);
        $orderId = $this->ensurePaymentReference($voucher);

        $query = array_filter([
            'order_id' => $orderId,
            'redirect' => $options['redirect'] ?? Config::get('services.pakasir.default_redirect_url'),
            'qris_only' => ($options['qris_only'] ?? false) ? 1 : null,
        ]);

        $queryString = $query ? '?' . http_build_query($query) : '';

        return sprintf('%s/pay/%s/%s%s', $this->baseUrl(), $slug, $amount, $queryString);
    }

    public function fetchTransactionStatus(Voucher $voucher): ?array
    {
        $apiKey = Config::get('services.pakasir.api_key');
        if (! $apiKey) {
            Log::warning('Pakasir API key is missing when checking transaction status.');
            return null;
        }

        $slug = $this->projectSlug();
        $orderId = $this->ensurePaymentReference($voucher);
        $amount = $this->normalizeAmount($voucher->total_harga);

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get($this->baseUrl() . '/api/transactiondetail', [
                    'project' => $slug,
                    'amount' => $amount,
                    'order_id' => $orderId,
                    'api_key' => $apiKey,
                ]);

            if ($response->failed()) {
                Log::warning('Pakasir status request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_id' => $orderId,
                ]);

                return null;
            }

            return $response->json('transaction');
        } catch (\Throwable $throwable) {
            Log::error('Unable to fetch Pakasir transaction status', [
                'message' => $throwable->getMessage(),
                'order_id' => $orderId,
            ]);

            return null;
        }
    }

    public function ensurePaymentReference(Voucher $voucher): string
    {
        if (filled($voucher->payment_reference)) {
            return $voucher->payment_reference;
        }

        $voucher->payment_reference = 'PKSR-' . strtoupper(Str::random(16));
        $voucher->save();

        return $voucher->payment_reference;
    }

    protected function projectSlug(): string
    {
        $slug = Config::get('services.pakasir.project_slug');

        if (! $slug) {
            throw new RuntimeException('Pakasir project slug is not configured.');
        }

        return $slug;
    }

    protected function baseUrl(): string
    {
        $base = Config::get('services.pakasir.base_url', 'https://app.pakasir.com');
        return rtrim($base, '/');
    }

    protected function normalizeAmount(float|int|string $value): int
    {
        return (int) round((float) $value);
    }
}
