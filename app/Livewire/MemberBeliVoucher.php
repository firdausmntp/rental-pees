<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\Tarif;
use Livewire\Component;
use Illuminate\Support\Str;

class MemberBeliVoucher extends Component
{
    public $tarif_id, $durasi_jam, $metode_pembayaran = 'cash';
    public $selectedTarif = null;
    public $totalHarga = 0;

    protected $rules = [
        'tarif_id' => 'required|exists:tarifs,id',
        'durasi_jam' => 'required|integer|min:1|max:24',
        'metode_pembayaran' => 'required|in:cash,qris',
    ];

    public function mount()
    {
        $this->durasi_jam = 1;
    }

    public function render()
    {
        $tarifs = Tarif::all();

        return view('livewire.member-beli-voucher', [
            'tarifs' => $tarifs,
        ])->layout('layouts.app');
    }

    public function updatedTarifId()
    {
        $this->resetValidation(['tarif_id']);
        $this->calculateTotal();
    }

    public function updatedDurasiJam()
    {
        $this->resetValidation(['durasi_jam']);
        $this->calculateTotal();
    }

    public function updatedMetodePembayaran()
    {
        $this->resetValidation(['metode_pembayaran']);
    }

    public function incrementDurasi()
    {
        if ($this->durasi_jam < 24) {
            $this->durasi_jam++;
            $this->calculateTotal();
        }
    }

    public function decrementDurasi()
    {
        if ($this->durasi_jam > 1) {
            $this->durasi_jam--;
            $this->calculateTotal();
        }
    }

    private function calculateTotal()
    {
        if ($this->tarif_id && $this->durasi_jam) {
            $tarif = Tarif::find($this->tarif_id);
            if ($tarif) {
                $this->selectedTarif = $tarif;
                $this->totalHarga = $tarif->harga_per_jam * $this->durasi_jam;
            }
        } else {
            $this->totalHarga = 0;
            $this->selectedTarif = null;
        }
    }

    public function beliVoucher()
    {
        \Log::info('beliVoucher method called!', [
            'user_id' => auth()->id(),
            'tarif_id' => $this->tarif_id,
            'durasi_jam' => $this->durasi_jam,
            'metode_pembayaran' => $this->metode_pembayaran
        ]);
        
        try {
            $this->validate();

            // Check if user is authenticated
            if (!auth()->check()) {
                \Log::error('User not authenticated');
                session()->flash('error', 'Silakan login terlebih dahulu.');
                return redirect()->route('login');
            }

            // Check if user role is member
            if (auth()->user()->role !== 'member') {
                \Log::error('User is not member', ['role' => auth()->user()->role]);
                session()->flash('error', 'Hanya member yang dapat membeli voucher.');
                return redirect()->route('dashboard');
            }

            // Get tarif untuk snapshot harga
            $tarif = Tarif::findOrFail($this->tarif_id);
            $hargaPerJam = $tarif->harga_per_jam;
            $totalHarga = $hargaPerJam * $this->durasi_jam;

            // Simpan metode pembayaran sebelum reset
            $metodePembayaran = $this->metode_pembayaran;

            \Log::info('Creating voucher without kode (pending)', [
                'total' => $totalHarga
            ]);

            // Create voucher WITHOUT kode_voucher (dibuat nanti saat kasir approve)
            $voucher = Voucher::create([
                'kode_voucher' => null, // NULL dulu, dibuat saat kasir approve jadi 'aktif'
                'member_id' => auth()->id(),
                'tarif_id' => $this->tarif_id,
                'durasi_jam' => $this->durasi_jam,
                'harga_per_jam' => $hargaPerJam,
                'total_harga' => $totalHarga,
                'status' => 'pending', // Status pending, menunggu approve kasir
                'metode_pembayaran' => $metodePembayaran,
                'status_pembayaran' => 'pending',
                'qris_image' => null,
                'tanggal_beli' => now(),
                'expired_at' => now()->addDays(30),
            ]);

            \Log::info('Voucher created successfully', [
                'voucher_id' => $voucher->id,
                'member_id' => auth()->id(),
                'total_harga' => $totalHarga,
                'status' => 'pending - belum ada kode'
            ]);

            // Success message
            if ($metodePembayaran === 'cash') {
                session()->flash('success', 'Voucher berhasil dibuat! Silakan bayar di kasir untuk mendapatkan kode voucher.');
            } else {
                session()->flash('success', 'Voucher berhasil dibuat! Silakan scan QRIS untuk pembayaran. Kode voucher akan diberikan setelah pembayaran dikonfirmasi kasir.');
            }
            
            // Reset form
            $this->reset(['tarif_id', 'durasi_jam', 'metode_pembayaran']);
            $this->durasi_jam = 1;
            $this->metode_pembayaran = 'cash';
            
            return redirect()->route('member.dashboard');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation error - show to user
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, implode(' ', $messages));
            }
            \Log::warning('Voucher validation failed', [
                'errors' => $e->errors(),
                'member_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            // General error
            $this->showConfirmation = false;
            session()->flash('error', 'Gagal membuat voucher: ' . $e->getMessage());
            \Log::error('Error creating voucher: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'member_id' => auth()->id(),
                'data' => [
                    'tarif_id' => $this->tarif_id,
                    'durasi_jam' => $this->durasi_jam,
                    'metode_pembayaran' => $this->metode_pembayaran
                ]
            ]);
        }
    }
}

