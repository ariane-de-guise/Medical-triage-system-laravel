@props(['disabled' => false])
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'hospital-input dark:bg-slate-800 dark:text-white dark:placeholder-slate-400']) !!}>
