<?php

namespace App\Livewire;

use App\Services\PaymentMethodManager;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class OwnerPaymentSettings extends Component
{
    public array $states = [];

    public function mount(PaymentMethodManager $manager): void
    {
        $this->states = $manager->getStates();
    }

    public function render()
    {
        return view('livewire.owner-payment-settings');
    }

    public function toggle(string $method): void
    {
        $manager = app(PaymentMethodManager::class);

        if (! $manager->getStates()[$method]['can_toggle'] ?? false) {
            session()->flash('error', 'Metode ini tidak dapat diubah dari aplikasi.');
            return;
        }

        $current = $manager->isEnabled($method);
        $disabledCount = $manager->toggle($method, ! $current);

        $this->states = $manager->getStates();

        if ($current) {
            $message = "Metode pembayaran {$method} berhasil dinonaktifkan.";
            if ($disabledCount > 0) {
                $message .= " {$disabledCount} transaksi pending tanpa bukti otomatis dibatalkan.";
            }
            session()->flash('error', $message);
        } else {
            session()->flash('success', "Metode pembayaran {$method} berhasil diaktifkan kembali.");
        }
    }
}
