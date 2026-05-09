<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <span x-show="!darkMode" x-cloak class="text-lg">
        <img
            src="{{ asset('logo/CTHQ.png') }}"
            alt="CTHQ"
            class="w-1/2 justify-content-center rounded-lg mb-6 w-20"
        />
    </span>
    <span x-show="darkMode" x-cloak class="text-lg">
        <img
            src="{{ asset('logo/CTHQ-dark.png') }}"
            alt="CTHQ"
            class="w-1/2 justify-content-center rounded-lg mb-6 w-20"
        />
    </span>
</svg>
