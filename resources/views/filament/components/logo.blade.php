@php
    $isLoginPage = request()->routeIs('filament.admin.auth.login');
@endphp

@if ($isLoginPage)
    <div class="flex justify-center relative bottom-8">
        <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-20">
    </div>
@else
    <div class="flex items-center text-nowrap gap-2">
        <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-14">
        <span class="text-xl font-bold text-white">Ayam Geprek Meriam</span>
    </div>
@endif