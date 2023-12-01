<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout("layouts.guest");

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirect(
        session('url.intended', '/'),
        navigate: true
    );
};
?>

<section>
    <form wire:submit="login">
        {{-- Admin Name --}}
        <div>
            <label class="block" htmlFor="adminName">Admin Username</label>
            <input id="adminName" type="text" wire:model="form.admin_username" placeholder="name@role" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="block" htmlFor="adminPassword">Password</label>
            <input id="adminPassword" type="password" wire:model="form.password" placeholder="••••••••••" />
        </div>

        <button class="bg-sky-600 text-white w-full mt-4 p-2 rounded-lg" type="submit">Log In</button>
    </form>
</section>