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
            <label htmlFor="adminName">Admin Name</label>
            <input id="adminName" type="text" wire:model="form.admin_name" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label htmlFor="adminPassword">Password</label>
            <input id="adminPassword" type="text" wire:model="form.password" />
        </div>

        <button type="submit">Log In</button>
    </form>
</section>