<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('logo/CTHQ.png') }}" type="image/x-icon">

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-40 right-[-8rem] h-[28rem] w-[28rem] rounded-full bg-cyan-400/20 blur-3xl"></div>
    <div class="absolute bottom-[-10rem] left-[-8rem] h-[30rem] w-[30rem] rounded-full bg-blue-900/15 blur-3xl"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.75),transparent_45%)]"></div>
</div>

<header class="sticky top-0 z-50 border-b border-white/50 bg-white/80 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
        <div class="flex items-center gap-4">
            <div class="grid h-14 w-14 place-items-center rounded-3xl bg-gradient-to-br from-blue-950 to-cyan-500 shadow-xl">
                <img src="{{ asset('logo/CTHQ.png') }}" alt="CTHQ" class="h-9 w-9 object-contain">
            </div>

            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-cyan-600">CTHQ</p>
                <h1 class="text-xl font-black text-slate-950">
                    Centre de triage des hôpitaux du Québec
                </h1>
            </div>
        </div>

        @if (Route::has('login'))
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="rounded-2xl bg-gradient-to-r from-blue-950 to-cyan-600 px-6 py-3 text-sm font-black text-white shadow-xl transition hover:scale-[1.02]">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-black text-slate-700 shadow-sm transition hover:border-cyan-300 hover:bg-cyan-50">
                        Se connecter
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rounded-2xl bg-gradient-to-r from-blue-950 to-cyan-600 px-6 py-3 text-sm font-black text-white shadow-xl transition hover:scale-[1.02]">
                            S'enregistrer
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </div>
</header>

<main>
    <section class="mx-auto grid max-w-7xl gap-12 px-6 py-16 lg:grid-cols-[1.05fr_.95fr] lg:items-center">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-200 bg-cyan-50 px-5 py-2 text-sm font-black text-cyan-700">
                <span class="h-2.5 w-2.5 rounded-full bg-cyan-500"></span>
                Système médical intelligent
            </div>

            <h2 class="mt-6 max-w-3xl text-5xl font-black leading-[1.05] text-slate-950 lg:text-7xl">
                Gestion moderne du
                <span class="bg-gradient-to-r from-blue-950 to-cyan-500 bg-clip-text text-transparent">
                    triage hospitalier
                </span>
            </h2>

            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                Une plateforme centralisée permettant aux infirmiers, médecins et administrateurs
                de gérer efficacement les patients, les priorités et les consultations.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('login') }}" class="rounded-3xl bg-gradient-to-r from-blue-950 to-cyan-600 px-8 py-4 text-lg font-black text-white shadow-2xl transition hover:scale-[1.02]">
                    Accéder au portail
                </a>

                <a href="#ressources" class="rounded-3xl border border-slate-200 bg-white px-8 py-4 text-lg font-black text-slate-700 shadow-sm transition hover:border-cyan-300 hover:bg-cyan-50">
                    Voir les ressources
                </a>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -left-8 top-8 h-48 w-48 rounded-full bg-cyan-400/20 blur-3xl"></div>

            <div class="relative overflow-hidden rounded-[2.5rem] border border-white/60 bg-white/80 p-5 shadow-[0_40px_100px_-20px_rgba(15,23,42,0.25)] backdrop-blur">
                <div>
                    <img src="{{ asset('logo/CTHQ.png') }}" alt="CTHQ" class="mx-auto w-full max-w-md object-contain drop-shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <section id="ressources" class="mx-auto max-w-7xl px-6 pb-20">
        <div class="mb-10">
            <p class="hospital-badge">Assistance</p>
            <h2 class="mt-4 text-5xl font-black text-slate-950">Ressources disponibles</h2>
            <p class="mt-3 max-w-3xl text-lg text-slate-500">
                Accès rapide aux ressources médicales, psychologiques et sociales disponibles au Québec.
            </p>
        </div>

        <div class="space-y-8">
            @foreach(config('ressources') as $categorie)
                <section class="hospital-card overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50 px-8 py-5">
                        <h3 class="text-3xl font-black text-slate-950">{{ $categorie['nom'] }}</h3>
                    </div>

                    <div class="grid gap-5 p-6 lg:grid-cols-2">
                        @foreach($categorie['items'] as $ressource)
                            <article class="group rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-2xl">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="text-2xl font-black text-slate-950">{{ $ressource['nom'] }}</h4>
                                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $ressource['desc'] }}</p>
                                    </div>

                                    <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-blue-950 to-cyan-500 text-2xl text-white shadow-xl">
                                        ✚
                                    </div>
                                </div>

                                <div class="mt-5 space-y-3">
                                    <div class="rounded-2xl bg-slate-50 p-4">
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Téléphone</p>
                                        <p class="mt-1 font-bold text-slate-800">{{ $ressource['tel'] }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50 p-4">
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Site web</p>

                                        @if($ressource['site'] === 'Variable')
                                            <p class="mt-1 font-bold text-slate-800">Variable</p>
                                        @else
                                            <a href="{{ $ressource['site'] }}" target="_blank" class="mt-1 inline-block font-bold text-cyan-700 underline underline-offset-4">
                                                {{ $ressource['site'] }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
</main>
</body>
</html>
