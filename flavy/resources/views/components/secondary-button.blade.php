<button {{ $attributes->merge(['type' => 'button', 'class' => 'hospital-btn-light']) }}>
    {{ $slot }}
</button>
