<div {{ $attributes->merge(['class' => 'grid gap-6 lg:grid-cols-12']) }}>
    <div class="lg:col-span-4">
        <div class="hospital-card p-6">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#075fae] font-black mb-4">⚕</div>
            <h3 class="text-xl font-black text-slate-900">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
        </div>
    </div>

    <div class="lg:col-span-8">
        <div class="hospital-card p-6 sm:p-8">
            {{ $content }}
        </div>
    </div>
</div>
