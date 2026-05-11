@props(['value'])
<label {{ $attributes->merge(['class' => 'mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400']) }}>
    {{ $value ?? $slot }}
</label>
