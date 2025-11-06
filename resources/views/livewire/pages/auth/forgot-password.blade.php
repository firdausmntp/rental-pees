<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.auth'), Title('Lupa Password')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="mx-auto flex min-h-screen w-full max-w-md flex-col justify-center px-4 py-12">
    <div class="space-y-8">
        <!-- Header -->
        <div class="space-y-4 text-center">
            <a href="{{ url('/') }}" class="inline-block">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" class="h-10 w-10">
                        <path d="M15.858 11.451c-.313.395-1.079.676-1.079.676l-5.696 2.046v-1.509l4.192-1.493c.476-.17.549-.412.162-.538-.386-.127-1.085-.09-1.56.08l-2.794.984v-1.566l.161-.054s.807-.286 1.942-.412c1.135-.125 2.525.017 3.616.43 1.23.39 1.368.962 1.056 1.356M9.625 8.883v-3.86c0-.453-.083-.87-.508-.988-.326-.105-.528.198-.528.65v9.664l-2.606-.827V2c1.108.206 2.722.692 3.59.985 2.207.757 2.955 1.7 2.955 3.825 0 2.071-1.278 2.856-2.903 2.072Zm-8.424 3.625C-.061 12.15-.271 11.41.304 10.984c.532-.394 1.436-.69 1.436-.69l3.737-1.33v1.515l-2.69.963c-.474.17-.547.411-.161.538.386.126 1.085.09 1.56-.08l1.29-.469v1.356l-.257.043a8.45 8.45 0 0 1-4.018-.323Z"/>
                    </svg>
                </div>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Lupa Password?</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Masukkan email untuk reset password</p>
            </div>
        </div>

        <x-auth-session-status class="alert alert-success" :status="session('status')" />

        <!-- Form Card -->
        <div class="space-y-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-xl">
            <form wire:submit="sendPasswordResetLink" class="space-y-5">
                <div class="form-control">
                    <label for="email" class="label">
                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Email</span>
                    </label>
                    <input wire:model="email" id="email" type="email" name="email" required autofocus
                           class="input input-bordered w-full bg-slate-50 dark:bg-slate-900 border-slate-300 dark:border-slate-600 focus:border-sky-500 focus:ring-sky-500" 
                           placeholder="email@contoh.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit" class="btn btn-primary btn-block bg-gradient-to-r from-sky-500 to-blue-600 border-0 text-white hover:from-sky-600 hover:to-blue-700">
                    <i class="bx bx-envelope text-xl"></i>
                    Kirim Link Reset
                </button>
            </form>
        </div>

        <!-- Login Link -->
        <div class="text-center text-sm text-slate-600 dark:text-slate-400">
            Ingat password Anda?
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300">
                Masuk di sini
            </a>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
                <i class="bx bx-arrow-back"></i>
                Kembali ke beranda
            </a>
        </div>
    </div>
</div>
</div>
