<button {{ $attributes->merge(['type' => 'submit', 'class' => 'hospital-btn']) }}>
    {{ $slot }}
</button>
