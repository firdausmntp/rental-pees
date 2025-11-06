<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use function Livewire\Volt\{layout};

layout('layouts.guest');

$logout = function () {
    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    $this->redirect('/', navigate: true);
};

?>

<div>
    <button wire:click="logout" style="display: none;" id="auto-logout"></button>
    <script>
        document.getElementById('auto-logout').click();
    </script>
</div>
