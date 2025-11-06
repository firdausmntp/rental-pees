<?php

use Illuminate\Support\Facades\Route;
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
use App\Livewire\MemberBeliVoucher;
use App\Livewire\MemberDashboard;
use App\Livewire\MemberJadwalPS;
use App\Livewire\ApproveTransaksi;
use App\Livewire\LiveMonitoring;

Route::view('/', 'welcome');

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
    });

    // Karyawan Routes - Limited Access
    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/karyawan/dashboard', Dashboard::class)->name('karyawan.dashboard');
        Route::view('/karyawan/profile', 'profile')->name('karyawan.profile');
        Route::get('/karyawan/users', UserManagement::class)->name('karyawan.users');
        Route::get('/karyawan/voucher', VoucherManagement::class)->name('karyawan.voucher');
        Route::get('/karyawan/voucher/redeem', VoucherRedeem::class)->name('karyawan.voucher.redeem');
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

require __DIR__.'/auth.php';

