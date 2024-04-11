<x-general-layout pageTitle="Home">
    {{-- <h1>Home</h1>
    <div>{{ auth()->user() }}</div> --}}
    <livewire:home :user="auth()->user()"/>
</x-general-layout>