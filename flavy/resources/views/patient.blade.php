<x-app-layout>
<x-slot name="header"><div><p class="hospital-badge">Patient</p><h2 class="mt-3 text-4xl font-black text-slate-950">Mes rendez-vous</h2><p class="mt-2 text-slate-500">File d'attente, statut et historique de visites.</p></div></x-slot>
@if(session('success'))<div class="mb-6 hospital-card border-green-200 bg-green-50 p-4 font-bold text-green-800">{{ session('success') }}</div>@endif
<div class="grid gap-6 xl:grid-cols-[.9fr_1.3fr]">
    <section class="hospital-card p-7">
        <h3 class="text-2xl font-black">Votre arrivée</h3>
        @if (\App\Algorithms\TriageQueue::enqueued(auth()->user()))
            <div class="mt-6 rounded-[2rem] bg-gradient-to-br from-blue-950 to-cyan-600 p-8 text-center text-white">
                <p class="text-sm font-bold uppercase tracking-[.2em] text-cyan-100">Position</p>
                <p class="mt-3 text-7xl font-black">{{ \App\Algorithms\TriageQueue::queuePosition(auth()->user()) }}</p>
                <p class="mt-3 text-blue-50">Restez disponible près de la salle d'attente.</p>
            </div>
            <form method="POST" action="{{ route('patient.dequeue') }}" class="mt-5">@csrf<button type="submit" class="hospital-btn-soft w-full">Sortir de la file d'attente</button></form>
        @else
            <div class="mt-6 rounded-[2rem] bg-slate-50 p-7"><p class="font-bold text-slate-700">Vous n'êtes pas encore dans la file.</p><p class="mt-2 text-slate-500">Cliquez lorsque vous êtes arrivé à l'hôpital.</p></div>
            <form method="POST" action="{{ route('patient.enqueue') }}" class="mt-5">@csrf<button type="submit" class="hospital-btn w-full">Je suis à l'hôpital</button></form>
        @endif
    </section>
    <section class="hospital-card p-7">
        <div class="flex items-center justify-between"><h3 class="text-2xl font-black">Visites précédentes</h3><span class="hospital-badge">Historique</span></div>
        @if(auth()->user()->compte->visites()->count() === 0)
            <div class="mt-6 rounded-3xl border-2 border-dashed border-slate-200 p-10 text-center text-slate-500">Aucune visite enregistrée.</div>
        @else
            <div class="mt-6 space-y-4">
                @foreach(auth()->user()->compte->visites()->orderByDesc('date_heure')->get() as $visite)
                    <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between"><h4 class="text-lg font-black text-slate-900">Visite du {{ \Carbon\Carbon::parse($visite->date_heure)->format('d/m/Y H:i') }}</h4><span class="hospital-badge">Priorité {{ $visite->diagnostique->priorite }}</span></div>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 text-sm text-slate-600"><p><strong class="text-slate-900">Docteur</strong><br>{{ $visite->docteur->prenom }} {{ $visite->docteur->nom }}</p><p><strong class="text-slate-900">Commentaire infirmière</strong><br>{{ $visite->diagnostique->commentaire ?: 'Aucun commentaire' }}</p><p class="md:col-span-2"><strong class="text-slate-900">Diagnostic / traitement</strong><br>{{ ($visite->diagnostique->description && $visite->diagnostique->description !== 'À remplir') ? $visite->diagnostique->description : 'Non renseigné' }}</p></div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
</x-app-layout>