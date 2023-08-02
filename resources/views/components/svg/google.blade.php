@props(['color'])

<svg xmlns="http://www.w3.org/2000/svg" width="192" height="192" fill="{{ $color }}" viewBox="0 0 256 256" {{ $attributes }}>
    <rect width="256" height="256" fill="none"></rect>
    <path d="M128,128h88a88.1,88.1,0,1,1-25.8-62.2" fill="none" stroke="{{ $color }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></path>
</svg>
