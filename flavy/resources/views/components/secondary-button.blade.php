<button {{ $attributes->merge(['type' => 'button', 'class' => 'hospital-btn-light dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700']) }}>
    {{ $slot }}
</button>
