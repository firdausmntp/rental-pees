<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-base-content">
            {{ __('Profil Pengguna') }}
        </h1>
    </x-slot>

    <div class="space-y-6">
        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="max-w-xl space-y-4">
                    <h2 class="text-lg font-semibold text-base-content">Informasi Akun</h2>
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>
        </div>

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="max-w-xl space-y-4">
                    <h2 class="text-lg font-semibold text-base-content">Perbarui Password</h2>
                    <livewire:profile.update-password-form />
                </div>
            </div>
        </div>

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="max-w-xl space-y-4">
                    <h2 class="text-lg font-semibold text-error">Hapus Akun</h2>
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
