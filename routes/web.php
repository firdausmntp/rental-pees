<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tarif;
use App\Livewire\Dashboard;
use App\Livewire\PlayStationManagement;
use App\Livewire\PelangganManagement;
use App\Livewire\UserManagement;
use App\Livewire\TarifManagement;
use App\Livewire\TransaksiSewa;
use App\Livewire\TransaksiKembali;
use App\Livewire\LaporanTransaksi;
use App\Livewire\VoucherManagement;
use App\Livewire\VoucherRedeem;
use App\Livewire\OwnerPaymentSettings;
use App\Livewire\MemberBeliVoucher;
use App\Livewire\MemberDashboard;
use App\Livewire\MemberJadwalPS;
use App\Livewire\ApproveTransaksi;
use App\Livewire\LiveMonitoring;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PakasirWebhookController;

Route::get('/', function () {
    $tarifPrices = Tarif::query()
        ->select('tipe_ps', 'harga_per_jam')
        ->get()
        ->mapWithKeys(fn ($tarif) => [$tarif->tipe_ps => (float) $tarif->harga_per_jam])
        ->toArray();

    return view('welcome', compact('tarifPrices'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect ke dashboard sesuai role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif ($user->role === 'karyawan') {
            return redirect()->route('karyawan.dashboard');
        } elseif ($user->role === 'member') {
            return redirect()->route('member.dashboard');
        }
        
        // Fallback jika role tidak dikenali
        abort(403, 'Role tidak valid');
    })->name('dashboard');
    
    Route::view('profile', 'profile')->name('profile');

    // Owner Routes - Full Access
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner/dashboard', Dashboard::class)->name('owner.dashboard');
        Route::view('/owner/profile', 'profile')->name('owner.profile');
        Route::get('/owner/playstation', PlayStationManagement::class)->name('owner.playstation');
        Route::get('/owner/users', UserManagement::class)->name('owner.users');
        Route::get('/owner/laporan', LaporanTransaksi::class)->name('owner.laporan');
        Route::get('/owner/voucher', VoucherManagement::class)->name('owner.voucher');
        Route::get('/owner/voucher/redeem', VoucherRedeem::class)->name('owner.voucher.redeem');
        Route::get('/owner/pengaturan/pembayaran', OwnerPaymentSettings::class)->name('owner.payments');
        Route::get('/owner/kompromi', \App\Livewire\Kompromi::class)->name('owner.kompromi');
    });

    // Karyawan Routes - Limited Access
    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/karyawan/dashboard', Dashboard::class)->name('karyawan.dashboard');
        Route::view('/karyawan/profile', 'profile')->name('karyawan.profile');
        Route::get('/karyawan/users', UserManagement::class)->name('karyawan.users');
        Route::get('/karyawan/voucher', VoucherManagement::class)->name('karyawan.voucher');
        Route::get('/karyawan/voucher/redeem', VoucherRedeem::class)->name('karyawan.voucher.redeem');
        Route::get('/karyawan/kompromi', \App\Livewire\Kompromi::class)->name('karyawan.kompromi');
    });

    // Member Only Routes
    Route::middleware(['role:member'])->group(function () {
        Route::get('/member/dashboard', MemberDashboard::class)->name('member.dashboard');
        Route::view('/member/profile', 'profile')->name('member.profile');
        Route::get('/member/beli-voucher', MemberBeliVoucher::class)->name('member.beli');
        Route::get('/member/jadwal-ps', MemberJadwalPS::class)->name('member.jadwal');
        Route::get('/member/redeem', VoucherRedeem::class)->name('member.redeem');
    });
});

if (config('services.midtrans.enabled')) {
    Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification']);
    Route::get('/midtrans/callback', [MidtransController::class, 'handleCallback'])->name('midtrans.callback');

    Route::get('/test-midtrans', function () {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'TEST-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'ITEM-1',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Test Item',
                ]
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return "Success! Token: " . $snapToken . "<br>Server Key: " . substr(config('services.midtrans.server_key'), 0, 5) . "...<br>Env: " . (config('services.midtrans.is_production') ? 'Production' : 'Sandbox');
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage() . "<br>Trace: " . $e->getTraceAsString();
        }
    });
}

Route::post('/pakasir/webhook', [PakasirWebhookController::class, 'handle']);

require __DIR__.'/auth.php';
