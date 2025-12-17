<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\Tarif;
use App\Services\PakasirGateway;
use App\Services\PaymentMethodManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class MemberBeliVoucher extends Component
{
    use WithFileUploads;

    public $tarif_id, $durasi_jam, $metode_pembayaran = 'cash';
    public array $availablePaymentMethods = [];
    public array $methodStates = [];
    public $selectedTarif = null;
    public $totalHarga = 0;
    public $showQrisModal = false;
    public $previewQrisNominal = null;
    public $buktiPembayaran;
    public $qrisData = null;
    // public $snapToken = null; // No longer needed for redirect

    protected function rules()
    {
        if (empty($this->availablePaymentMethods)) {
            $this->syncPaymentOptions();
        }

        return [
            'tarif_id' => 'required|exists:tarifs,id',
            'durasi_jam' => 'required|integer|min:1|max:24',
            'metode_pembayaran' => 'required|in:' . implode(',', $this->availablePaymentMethods),
        ];
    }

    public function mount(PaymentMethodManager $manager)
    {
        $this->durasi_jam = 1;
        $this->syncPaymentOptions($manager);
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
        $this->resetQrisPreview();
    }

    public function updatedDurasiJam()
    {
        $this->resetValidation(['durasi_jam']);
        $this->calculateTotal();
        $this->resetQrisPreview();
    }

    public function updatedMetodePembayaran()
    {
        $this->resetValidation(['metode_pembayaran']);

        if (! in_array($this->metode_pembayaran, $this->availablePaymentMethods, true)) {
            $this->metode_pembayaran = $this->availablePaymentMethods[0] ?? 'cash';
        }

        if ($this->metode_pembayaran !== 'qris') {
            $this->resetQrisPreview();
        }
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

    public function preparePayment()
    {
        $this->validate();

        $this->syncPaymentOptions();

        if (! in_array($this->metode_pembayaran, $this->availablePaymentMethods, true)) {
            session()->flash('error', 'Metode pembayaran tidak tersedia saat ini. Silakan pilih metode lain.');
            return redirect()->route('member.beli');
        }

        if ($this->metode_pembayaran === 'qris') {
            $this->prepareQrisModal();
            return;
        }

        if ($this->metode_pembayaran === 'pakasir') {
            return $this->processPakasirPayment();
        }

        if ($this->metode_pembayaran === 'midtrans') {
            return $this->processMidtransPayment();
        }

        $this->finalizeVoucher();
    }

    public function processMidtransPayment()
    {
        if (! config('services.midtrans.enabled')) {
            session()->flash('error', 'Pembayaran Midtrans sementara tidak tersedia.');
            return redirect()->route('member.beli');
        }

        // Create voucher first
        $voucher = $this->finalizeVoucher(null, true);
        
        if (!$voucher) {
            return;
        }

        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Debug log - Check Midtrans Config
        \Log::info('=== MIDTRANS CONFIG CHECK ===', [
            'server_key' => Config::$serverKey,
            'is_production' => Config::$isProduction ? 'PRODUCTION' : 'SANDBOX',
            'merchant_id' => config('services.midtrans.merchant_id'),
            'client_key' => config('services.midtrans.client_key'),
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => 'V-' . $voucher->id . '-' . time(),
                'gross_amount' => (int) $voucher->harga_per_jam * (int) $voucher->durasi_jam, // Ensure match with item_details
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->no_hp ?? '08123456789', // Use user phone if available or dummy
            ],
            'item_details' => [
                [
                    'id' => 'PS-' . $voucher->tarif->tipe_ps,
                    'price' => (int) $voucher->harga_per_jam,
                    'quantity' => (int) $voucher->durasi_jam,
                    'name' => substr('PlayStation ' . $voucher->tarif->tipe_ps . ' (' . $voucher->durasi_jam . ' Jam)', 0, 50),
                ]
            ],
            'enabled_payments' => [
                // Virtual Account Banks
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
                
                // E-Wallets
                'gopay',
                'shopeepay',
                'qris',
                
                // Credit Card (optional, can be removed if not needed)
                'credit_card',
            ],
            'callbacks' => [
                'finish' => route('midtrans.callback'),
            ],
        ];

        \Log::info('Midtrans Params:', $params);

        try {
            // Use createTransaction to get redirect_url instead of just token
            $transaction = Snap::createTransaction($params);
            \Log::info('Midtrans Transaction Created:', [
                'token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url
            ]);
            
            // Update voucher with payment reference and snap token
            $voucher->update([
                'payment_reference' => $params['transaction_details']['order_id'],
                'snap_token' => $transaction->token
            ]);
            
            // Redirect to Snap payment page using the correct redirect_url
            return redirect()->away($transaction->redirect_url);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error Details:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'params' => $params,
            ]);
            
            session()->flash('error', 'Gagal memproses pembayaran Midtrans: ' . $e->getMessage());
            return redirect()->route('member.beli');
        }
    }

    public function processPakasirPayment()
    {
        if (! config('services.pakasir.enabled')) {
            session()->flash('error', 'Pembayaran Pakasir belum diaktifkan.');
            return redirect()->route('member.beli');
        }

        $voucher = $this->finalizeVoucher(null, true);

        if (! $voucher) {
            return null;
        }

        try {
            $gateway = app(PakasirGateway::class);
            $redirectUrl = $gateway->buildPaymentUrl($voucher, [
                'redirect' => route('member.dashboard'),
            ]);
        } catch (\Throwable $throwable) {
            \Log::error('Failed to prepare Pakasir payment', [
                'message' => $throwable->getMessage(),
                'voucher_id' => $voucher->id ?? null,
            ]);

            session()->flash('error', 'Konfigurasi Pakasir belum lengkap. Hubungi admin.');
            return redirect()->route('member.dashboard');
        }

        session()->flash('success', 'Voucher berhasil dibuat. Kami mengarahkan Anda ke halaman pembayaran Pakasir.');

        return redirect()->away($redirectUrl);
    }

    public function openQrisPreview()
    {
        $this->validate([
            'tarif_id' => 'required|exists:tarifs,id',
            'durasi_jam' => 'required|integer|min:1|max:24',
        ]);

        $this->prepareQrisModal();
    }

    public function closeQrisModal()
    {
        // Jika user menutup modal (Batal/Bayar Nanti), kita tetap buat vouchernya
        // agar user bisa bayar nanti lewat dashboard
        if ($this->previewQrisNominal) {
            $this->finalizeVoucher($this->previewQrisNominal);
        } else {
            $this->showQrisModal = false;
            $this->buktiPembayaran = null;
        }
    }

    public function confirmQrisPayment()
    {
        $this->validate([
            'buktiPembayaran' => 'required|image|max:2048',
        ]);

        if (!$this->previewQrisNominal) {
            $this->prepareQrisModal();
        }

        $this->finalizeVoucher($this->previewQrisNominal);
    }

    private function prepareQrisModal()
    {
        if (!$this->totalHarga) {
            $this->calculateTotal();
        }

        if (!$this->selectedTarif) {
            $this->selectedTarif = Tarif::find($this->tarif_id);
        }

        if (!$this->selectedTarif) {
            return;
        }

        if (!$this->previewQrisNominal) {
            $this->previewQrisNominal = Voucher::generateUniqueQrisNominal($this->selectedTarif->harga_per_jam * $this->durasi_jam);
        }

        // Generate QRIS Data
        $this->qrisData = $this->generateQrisString($this->previewQrisNominal);

        $this->showQrisModal = true;
    }

    private function generateQrisString($amount)
    {
        // Base QRIS string from user (GoPay/GoJek)
        // Original: 00020101021126610014COM.GO-JEK.WWW01189360091430404966290210G0404966290303UMI51440014ID.CO.QRIS.WWW0215ID10243614510490303UMI5204549953033605802ID5919JSK STORE, SNDNG JY6009TANGERANG61051556062070703A016304B469
        
        // Step 1: Remove CRC (last 4 chars)
        // Base without CRC: ...62070703A01
        $baseQris = "00020101021126610014COM.GO-JEK.WWW01189360091430404966290210G0404966290303UMI51440014ID.CO.QRIS.WWW0215ID10243614510490303UMI5204549953033605802ID5919JSK STORE, SNDNG JY6009TANGERANG61051556062070703A01";
        
        // Step 2: Change 010211 (Static) to 010212 (Dynamic)
        $step1 = str_replace("010211", "010212", $baseQris);
        
        // Step 3: Split at 5802ID to inject amount
        $parts = explode("5802ID", $step1);
        
        // Step 4: Create Tag 54 (Amount)
        $amountStr = (string) $amount;
        $amountTag = "54" . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
        
        // Step 5: Reassemble
        // Part 0 contains everything up to 5802ID
        // We need to inject amountTag before 5802ID
        // Note: The original string has 5802ID... so explode removes it. We need to add it back.
        
        $fix = $parts[0] . $amountTag . "5802ID" . $parts[1] . "6304";
        
        // Step 6: Calculate CRC
        $crc = $this->crc16($fix);
        
        return $fix . $crc;
    }

    private function crc16($str)
    {
        // CRC16-CCITT (0x1021) implementation provided by user
        $crc = 0xFFFF;
        $strlen = strlen($str);
        
        for($c = 0; $c < $strlen; $c++) {
            $crc ^= ord($str[$c]) << 8;
            for($i = 0; $i < 8; $i++) {
                if($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        
        $hex = $crc & 0xFFFF;
        $hex = strtoupper(dechex($hex));
        
        // Pad to 4 chars
        return str_pad($hex, 4, '0', STR_PAD_LEFT);
    }

    private function finalizeVoucher(?float $forcedQrisNominal = null, bool $returnVoucher = false)
    {
        \Log::info('beliVoucher method called!', [
            'user_id' => auth()->id(),
            'tarif_id' => $this->tarif_id,
            'durasi_jam' => $this->durasi_jam,
            'metode_pembayaran' => $this->metode_pembayaran
        ]);
        
        try {
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

            $qrisNominal = null;
            $paymentGateway = null;
            $qrisImagePath = null;
            $paymentReference = null;

            if ($metodePembayaran === 'qris') {
                $qrisNominal = $forcedQrisNominal ?? Voucher::generateUniqueQrisNominal($totalHarga);
                $paymentGateway = 'manual_qris';

                if ($this->buktiPembayaran) {
                    $qrisImagePath = $this->buktiPembayaran->store('qris-payments', 'public');
                }
            } elseif ($metodePembayaran === 'pakasir') {
                $paymentGateway = 'pakasir';
                $paymentReference = 'PKSR-' . strtoupper(Str::random(16));
            } elseif ($metodePembayaran === 'midtrans') {
                $paymentGateway = 'midtrans';
            }

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
                'qris_nominal' => $qrisNominal,
                'status' => 'pending', // Status pending, menunggu approve kasir
                'metode_pembayaran' => $metodePembayaran,
                'status_pembayaran' => 'pending',
                'payment_gateway' => $paymentGateway,
                'payment_reference' => $paymentReference,
                'qris_image' => $qrisImagePath,
                'tanggal_beli' => now(),
                'expired_at' => now()->addDays(30),
            ]);

            \Log::info('Voucher created successfully', [
                'voucher_id' => $voucher->id,
                'member_id' => auth()->id(),
                'total_harga' => $totalHarga,
                'status' => 'pending - belum ada kode'
            ]);

            if ($returnVoucher) {
                return $voucher;
            }

            // Success message
            if ($metodePembayaran === 'cash') {
                session()->flash('success', 'Voucher berhasil dibuat! Silakan bayar di kasir untuk mendapatkan kode voucher.');
            } elseif ($metodePembayaran === 'pakasir') {
                session()->flash('success', 'Voucher pending berhasil dibuat! Anda akan diarahkan ke halaman pembayaran otomatis.');
            } else {
                session()->flash(
                    'success',
                    'Voucher pending berhasil dibuat! Nominal unik Rp ' .
                    number_format($qrisNominal, 0, ',', '.') .
                    ' tercatat dan bukti pembayaran akan dicek manual.'
                );
            }

            $this->resetFormState();

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

    private function resetFormState()
    {
        $this->reset(['tarif_id', 'durasi_jam', 'metode_pembayaran', 'showQrisModal', 'previewQrisNominal', 'buktiPembayaran']);
        $this->durasi_jam = 1;
        $this->syncPaymentOptions();
    }

    private function resetQrisPreview()
    {
        $this->previewQrisNominal = null;
        $this->buktiPembayaran = null;
        $this->showQrisModal = false;
    }

    private function syncPaymentOptions(?PaymentMethodManager $manager = null): void
    {
        $manager ??= app(PaymentMethodManager::class);
        $this->methodStates = $manager->getStates();
        $this->availablePaymentMethods = $manager->availableMethods();

        if (! in_array($this->metode_pembayaran, $this->availablePaymentMethods, true)) {
            $this->metode_pembayaran = $this->availablePaymentMethods[0] ?? 'cash';
        }
    }
}

