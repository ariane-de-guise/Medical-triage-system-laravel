<button {{ $attributes->merge(['type' => 'submit', 'class' => 'hospital-btn dark:bg-blue-600 dark:hover:bg-blue-700']) }}>
    {{ $slot }}
</button>
