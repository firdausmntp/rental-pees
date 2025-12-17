<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\User;
use App\Models\Tarif;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class VoucherManagement extends Component
{
    use WithPagination;

    public $member_id, $tarif_id, $durasi_jam, $expired_days = 30;
    public $isOpen = false;
    public $search = '';
    public $statusFilter = '';
    public $nama_pembeli = ''; // Untuk voucher tanpa member
    public $metode_pembayaran = 'cash'; // cash atau qris
    
    // Modal confirmation
    public $confirmingDelete = false;
    public $confirmingApprove = false;
    public $confirmingReject = false;
    public $selectedVoucherId = null;
    
    // Modal image
    public $showImageModal = false;
    public $selectedImage = null;

    // QRIS preview modal
    public $showQrisModal = false;
    public $qrisData = null;
    public $previewQrisNominal = null;

    protected $rules = [
        'tarif_id' => 'required|exists:tarifs,id',
        'durasi_jam' => 'required|integer|min:1',
        'expired_days' => 'required|integer|min:1|max:365',
        'metode_pembayaran' => 'required|in:cash,qris',
    ];

    protected function rules()
    {
        return [
            'tarif_id' => 'required|exists:tarifs,id',
            'durasi_jam' => 'required|integer|min:1',
            'expired_days' => 'required|integer|min:1|max:365',
            'metode_pembayaran' => 'required|in:cash,qris',
            'member_id' => $this->nama_pembeli ? 'nullable' : 'required|exists:users,id',
            'nama_pembeli' => $this->member_id ? 'nullable' : 'required|string|max:255',
        ];
    }

    public function render()
    {
        $vouchers = Voucher::with(['member', 'tarif', 'transaksi'])
            ->when($this->search, function($query) {
                $search = $this->search;
                $query->where(function($subQuery) use ($search) {
                    $subQuery->where('kode_voucher', 'like', '%' . $search . '%')
                        ->orWhere('nama_pembeli', 'like', '%' . $search . '%')
                        ->orWhereHas('member', function($memberQuery) use ($search) {
                            $memberQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function($query) {
                if ($this->statusFilter === 'pending') {
                    $query->where('status_pembayaran', 'pending');
                } else {
                    $query->where('status', $this->statusFilter);
                }
            })
            ->latest()
            ->paginate(10);

        $filterSummary = [
            'total' => Voucher::count(),
            'active' => Voucher::where('status', 'aktif')->count(),
            'used' => Voucher::where('status', 'terpakai')->count(),
            'expired' => Voucher::where('status', 'expired')->count(),
            'pending' => Voucher::where('status_pembayaran', 'pending')->count(),
            'todayRevenue' => (float) Voucher::where('metode_pembayaran', '!=', 'kompromi')
                ->where(function($query) {
                    // Voucher yang dibeli hari ini dan sudah dibayar
                    $query->where(function($subQuery) {
                        $subQuery->whereDate('tanggal_beli', now())
                            ->where('status_pembayaran', 'paid')
                            ->whereNotNull('member_id');
                    })
                    // ATAU voucher yang sudah dipakai (redeemed) hari ini
                    ->orWhere(function($subQuery) {
                        $subQuery->where('status', 'terpakai')
                            ->whereDate('tanggal_pakai', now());
                    });
                })
                ->sum('total_harga'),
        ];

        $members = User::where('role', 'member')->get();
        $tarifs = Tarif::all();

        return view('livewire.voucher-management', [
            'vouchers' => $vouchers,
            'members' => $members,
            'tarifs' => $tarifs,
            'filterSummary' => $filterSummary,
        ])->layout('layouts.app');
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->member_id = '';
        $this->tarif_id = '';
        $this->durasi_jam = '';
        $this->expired_days = 30;
        $this->nama_pembeli = '';
        $this->metode_pembayaran = 'cash';
    }

    public function store()
    {
        $this->validate();

        $kodeVoucher = 'VCH-' . strtoupper(Str::random(8));
        
        // Get tarif untuk snapshot harga
        $tarif = Tarif::findOrFail($this->tarif_id);
        $hargaPerJam = $tarif->harga_per_jam;
        $totalHarga = $hargaPerJam * $this->durasi_jam;

        $qrisNominal = null;
        $paymentGateway = null;

        // Tentukan status berdasarkan metode pembayaran
        if ($this->metode_pembayaran === 'cash') {
            $statusPembayaran = 'paid';
            $statusVoucher = 'aktif';
        } else {
            $statusPembayaran = 'pending';
            $statusVoucher = 'pending';
            $qrisNominal = Voucher::generateUniqueQrisNominal($totalHarga);
            $paymentGateway = 'manual_qris';
        }

        Voucher::create([
            'kode_voucher' => $kodeVoucher,
            'member_id' => $this->member_id ?: null, // Bisa null jika tanpa member
            'tarif_id' => $this->tarif_id,
            'durasi_jam' => $this->durasi_jam,
            'harga_per_jam' => $hargaPerJam,
            'total_harga' => $totalHarga,
            'qris_nominal' => $qrisNominal,
            'metode_pembayaran' => $this->metode_pembayaran,
            'status' => $statusVoucher,
            'status_pembayaran' => $statusPembayaran,
            'nama_pembeli' => $this->nama_pembeli ?: null, // Nama custom jika tanpa member
            'payment_gateway' => $paymentGateway,
            'payment_reference' => null,
            'tanggal_beli' => now(),
            'expired_at' => now()->addDays($this->expired_days),
            'qris_image' => null, // QRIS akan diupload oleh pembeli
        ]);

        if ($this->metode_pembayaran === 'cash') {
            session()->flash('message', 'Voucher berhasil dibuat! Kode: ' . $kodeVoucher . ' (LUNAS)');
        } else {
            session()->flash(
                'message',
                'Voucher QRIS dibuat! Kode: ' . $kodeVoucher . ' — mohon terima transfer sebesar Rp ' .
                number_format($qrisNominal, 0, ',', '.') . ' dan unggahan bukti sebelum approve.'
            );
        }
        
        $this->closeModal();
    }

    public function deleteVoucher($id)
    {
        Voucher::find($id)->delete();
        session()->flash('message', 'Voucher berhasil dihapus');
    }

    public function approvePayment($id)
    {
        $voucher = Voucher::find($id);
        
        if (!$voucher || $voucher->status_pembayaran !== 'pending') {
            $this->dispatch('show-toast', [
                'message' => 'Voucher tidak ditemukan atau sudah diproses',
                'type' => 'error'
            ]);
            return;
        }

        // Generate kode voucher jika belum ada (untuk voucher pending)
        $kodeVoucher = $voucher->kode_voucher ?? 'VCH-' . strtoupper(\Illuminate\Support\Str::random(8));

        $voucher->update([
            'kode_voucher' => $kodeVoucher,
            'status_pembayaran' => 'paid',
            'status' => 'aktif',
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Pembayaran berhasil dikonfirmasi! Voucher ' . $kodeVoucher . ' aktif',
            'type' => 'success'
        ]);
    }

    public function cancelPayment($id)
    {
        $voucher = Voucher::find($id);
        
        if (!$voucher || $voucher->status_pembayaran !== 'pending') {
            $this->dispatch('show-toast', [
                'message' => 'Voucher tidak ditemukan atau sudah diproses',
                'type' => 'error'
            ]);
            return;
        }

        $voucher->delete();

        $this->dispatch('show-toast', [
            'message' => 'Pembayaran dibatalkan, voucher dihapus',
            'type' => 'warning'
        ]);
    }
    
    // Modal confirmation methods
    public function confirmDelete($id)
    {
        $this->selectedVoucherId = $id;
        $this->confirmingDelete = true;
    }
    
    public function confirmApprove($id)
    {
        $this->selectedVoucherId = $id;
        $this->confirmingApprove = true;
    }
    
    public function confirmReject($id)
    {
        $this->selectedVoucherId = $id;
        $this->confirmingReject = true;
    }
    
    public function executeDelete()
    {
        if ($this->selectedVoucherId) {
            $this->deleteVoucher($this->selectedVoucherId);
        }
        $this->confirmingDelete = false;
        $this->selectedVoucherId = null;
    }
    
    public function executeApprove()
    {
        if ($this->selectedVoucherId) {
            $this->approvePayment($this->selectedVoucherId);
        }
        $this->confirmingApprove = false;
        $this->selectedVoucherId = null;
    }
    
    public function executeReject()
    {
        if ($this->selectedVoucherId) {
            $this->cancelPayment($this->selectedVoucherId);
        }
        $this->confirmingReject = false;
        $this->selectedVoucherId = null;
    }
    
    public function cancelConfirmation()
    {
        $this->confirmingDelete = false;
        $this->confirmingApprove = false;
        $this->confirmingReject = false;
        $this->selectedVoucherId = null;
    }
    
    public function showImage($imagePath)
    {
        $this->selectedImage = $imagePath;
        $this->showImageModal = true;
    }
    
    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->selectedImage = null;
    }

    public function showQris($voucherId): void
    {
        $voucher = Voucher::find($voucherId);

        if (! $voucher || $voucher->metode_pembayaran !== 'qris') {
            return;
        }

        $this->previewQrisNominal = $voucher->qris_nominal ?? $voucher->total_harga;
        $this->qrisData = $this->generateQrisString($this->previewQrisNominal);
        $this->showQrisModal = true;
    }

    public function closeQrisModal(): void
    {
        $this->showQrisModal = false;
        $this->qrisData = null;
        $this->previewQrisNominal = null;
    }

    private function generateQrisString($amount): string
    {
        $baseQris = "00020101021126610014COM.GO-JEK.WWW01189360091430404966290210G0404966290303UMI51440014ID.CO.QRIS.WWW0215ID10243614510490303UMI5204549953033605802ID5919JSK STORE, SNDNG JY6009TANGERANG61051556062070703A01";

        $step1 = str_replace('010211', '010212', $baseQris);
        $parts = explode('5802ID', $step1);

        $amountStr = (string) $amount;
        $amountTag = '54' . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;

        $fixed = $parts[0] . $amountTag . '5802ID' . $parts[1] . '6304';
        $crc = $this->crc16($fixed);

        return $fixed . $crc;
    }

    private function crc16(string $payload): string
    {
        $crc = 0xFFFF;
        $length = strlen($payload);

        for ($index = 0; $index < $length; $index++) {
            $crc ^= ord($payload[$index]) << 8;
            for ($bit = 0; $bit < 8; $bit++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc <<= 1;
                }
            }
        }

        $hex = strtoupper(dechex($crc & 0xFFFF));
        return str_pad($hex, 4, '0', STR_PAD_LEFT);
    }
}

