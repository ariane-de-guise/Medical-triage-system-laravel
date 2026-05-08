<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CTHQ') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('logo/CTHQ.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900">
<x-banner />
@php
    $compte = auth()->user()?->compte;
    $initiales = strtoupper(substr($compte->prenom ?? auth()->user()->name ?? 'U', 0, 1).substr($compte->nom ?? '', 0, 1));
@endphp
<div class="min-h-screen lg:flex">
    <aside class="hidden lg:flex lg:w-80 lg:flex-col bg-gradient-to-b from-blue-950 via-blue-900 to-cyan-800 text-white p-6 shadow-2xl fixed inset-y-0 left-0">
        <div class="flex items-center gap-3 mb-10">
            <div class="h-12 w-12 rounded-2xl bg-white/95 p-2 shadow-lg"><img src="{{ asset('logo/CTHQ.png') }}" class="h-full w-full object-contain" alt="CTHQ"></div>
            <div><p class="text-xs uppercase tracking-[0.35em] text-cyan-100">Centre</p><h1 class="text-2xl font-black">CTHQ</h1></div>
        </div>
        <nav class="space-y-2 text-sm font-semibold">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>▦</span> Tableau de bord</a>
            @role('patient')<a href="{{ route('patient') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('patient') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>♡</span> Mes rendez-vous</a>@endrole
            @role('medecin')<a href="{{ route('medecin') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('medecin*') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>✚</span> Espace médecin</a>@endrole
            @role('medecin')<a href="{{ route('patients.liste') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('patients*') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>☷</span> Liste patients</a>@endrole
            @role('infirmier')<a href="{{ route('infirmier') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('infirmier*') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>●</span> Triage infirmier</a>@endrole
            @role('admin')<a href="{{ route('admin') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin*') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>⚙</span> Administration</a>@endrole
            @role('admin')<a href="{{ route('patients.liste') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('patients*') ? 'bg-white text-blue-950 shadow-xl' : 'text-blue-50 hover:bg-white/10' }}"><span>☷</span> Liste patients</a>@endrole
            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())<a href="{{ route('api-tokens.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-blue-50 hover:bg-white/10 transition"><span>⌁</span> API</a>@endif
        </nav>
        <div class="mt-auto rounded-3xl bg-white/10 p-5 backdrop-blur border border-white/10">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-cyan-300 text-blue-950 grid place-items-center font-black">{{ $initiales }}</div>
                <div class="min-w-0"><p class="font-bold truncate">{{ $compte->prenom ?? '' }} {{ $compte->nom ?? auth()->user()->name }}</p><p class="text-xs text-cyan-100 truncate">{{ auth()->user()->email }}</p></div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ route('profile.show') }}" class="text-center rounded-xl bg-white/10 px-3 py-2 text-xs font-bold hover:bg-white/20">Profil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full rounded-xl bg-white text-blue-950 px-3 py-2 text-xs font-black">Sortir</button></form>
            </div>
        </div>
    </aside>
    <div class="lg:pl-80 w-full">
        <header class="lg:hidden bg-blue-950 text-white p-4 flex items-center justify-between shadow-xl">
            <a href="{{ route('dashboard') }}" class="font-black text-xl">CTHQ</a>
            <div class="flex items-center gap-3"><a href="{{ route('profile.show') }}" class="rounded-xl bg-white/10 px-3 py-2 text-sm">Profil</a><form method="POST" action="{{ route('logout') }}">@csrf<button class="rounded-xl bg-white text-blue-950 px-3 py-2 text-sm font-bold">Sortir</button></form></div>
        </header>
        @if (isset($header))
            <div class="px-4 sm:px-8 pt-8">{{ $header }}</div>
        @endif
        <main class="px-4 sm:px-8 py-8">{{ $slot }}</main>
    </div>
</div>
@stack('modals')
@livewireScripts
</body>
</html>