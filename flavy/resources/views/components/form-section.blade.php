@props(['submit'])

<div {{ $attributes->merge(['class' => 'grid gap-6 lg:grid-cols-12']) }}>
    <div class="lg:col-span-4">
        <div class="hospital-card p-6 sticky top-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#075fae] font-black mb-4">+</div>
            <h3 class="text-xl font-black text-slate-900">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
        </div>
    </div>

    <div class="lg:col-span-8">
        <form wire:submit="{{ $submit }}" class="hospital-card overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-6 gap-5">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/80 px-6 py-4 sm:px-8">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
