@props(['name' => null, 'label' => null])

@php
    if ($label) $label = __($label);
@endphp

<label for="{{ $label ?? $name }}" class="font-medium text-sm text-gray-700 dark:text-white ml-2">
    {{ $label ?? $slot }}
</label>
