<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="hospital-badge">Accueil sécurisé</p>
                <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-950">Bonjour {{ auth()->user()->compte->prenom ?? auth()->user()->name }}</h2>
                <p class="mt-2 text-slate-500">Votre espace CTHQ, adapté à votre rôle.</p>
            </div>
        </div>
    </x-slot>

    @role('patient')
        <section class="grid gap-6 xl:grid-cols-[1.4fr_.8fr]">
            <div class="hospital-card overflow-hidden">
                <div class="bg-gradient-to-br from-blue-950 via-blue-800 to-cyan-600 p-8 text-white">
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-cyan-100">Espace patient</p>
                    <h1 class="mt-4 text-4xl font-black">Vos rendez-vous et visites</h1>
                    <p class="mt-3 max-w-2xl text-blue-50">Ici, vous pouvez rejoindre la file d'attente, consulter votre position et revoir vos visites précédentes.</p>
                    <a href="{{ route('patient') }}" class="mt-6 inline-flex rounded-2xl bg-white px-5 py-3 font-black text-blue-950 shadow-xl">Ouvrir mon espace</a>
                </div>
            </div>
            <div class="hospital-card p-7">
                <h3 class="text-xl font-black">État actuel</h3>
                @if (\App\Algorithms\TriageQueue::enqueued(auth()->user()))
                    <div class="mt-5 rounded-3xl bg-cyan-50 p-6 text-center">
                        <p class="text-sm font-bold text-cyan-700">Position dans la file</p>
                        <p class="mt-2 text-6xl font-black text-blue-950">{{ \App\Algorithms\TriageQueue::queuePosition(auth()->user()) }}</p>
                    </div>
                @else
                    <p class="mt-4 rounded-3xl bg-slate-50 p-5 text-slate-600">Vous n'êtes pas actuellement dans la file d'attente.</p>
                @endif
            </div>
        </section>
    @else
        <section class="space-y-6">
            <div class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-800 to-cyan-600 p-8 text-white shadow-2xl shadow-blue-950/20">
                <div class="grid gap-8 lg:grid-cols-[1.4fr_.8fr] lg:items-center">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-cyan-100">Centre hospitalier</p>
                        <h1 class="mt-4 text-5xl font-black leading-tight">Tableau de bord clinique</h1>
                        <p class="mt-4 max-w-2xl text-blue-50">Accès rapide aux files d'attente, consultations, rôles et opérations internes.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-3xl bg-white/15 p-5 backdrop-blur"><p class="text-3xl font-black">24/7</p><p class="text-sm text-cyan-100">Service</p></div>
                        <div class="rounded-3xl bg-white/15 p-5 backdrop-blur"><p class="text-3xl font-black">CTHQ</p><p class="text-sm text-cyan-100">Réseau</p></div>
                    </div>
                </div>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @role('admin')<a href="{{ route('admin') }}" class="hospital-card p-6 hover:-translate-y-1 transition"><p class="hospital-badge">Admin</p><h3 class="mt-4 text-2xl font-black">Administration</h3><p class="mt-2 text-slate-500">Utilisateurs, rôles et gestion générale.</p></a>@endrole
                @role('medecin')<a href="{{ route('medecin') }}" class="hospital-card p-6 hover:-translate-y-1 transition"><p class="hospital-badge">Médecin</p><h3 class="mt-4 text-2xl font-black">Consultations</h3><p class="mt-2 text-slate-500">Prendre en charge les patients triés.</p></a>@endrole
                @role('infirmier')<a href="{{ route('infirmier') }}" class="hospital-card p-6 hover:-translate-y-1 transition"><p class="hospital-badge">Infirmier</p><h3 class="mt-4 text-2xl font-black">Triage</h3><p class="mt-2 text-slate-500">Évaluer les symptômes et priorités.</p></a>@endrole
            </div>
        </section>
    @endrole
</x-app-layout>