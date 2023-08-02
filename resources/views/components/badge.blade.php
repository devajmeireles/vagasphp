@props([
    'label' => null,

    'sm' => null,
    'md' => null,
    'lg' => null,
    'xl' => null,

    'type' => null,

    'green'  => null,
    'red'    => null,
    'yellow' => null,
    'blue'   => null,
    'gray'   => null,
])

@php
    $color = 'text-primary-100 bg-primary-600';

    if ($type === 'green' || $green)   $color = 'text-green-100 bg-green-600';
    if ($type === 'red' || $red)       $color = 'text-red-100 bg-red-600';
    if ($type === 'yellow' || $yellow) $color = 'text-yellow-100 bg-yellow-600';
    if ($type === 'blue' || $blue)     $color = 'text-blue-100 bg-blue-600';
    if ($type === 'gray' || $gray)     $color = 'text-gray-700 bg-gray-200';

    $size = 'text-xs px-2.5 py-0.5';

    if ($sm) $size = 'text-sm px-3 py-0.5';
    if ($md) $size = 'text-sm px-3 py-0.5';
    if ($lg) $size = 'text-base px-4 py-1';
    if ($xl) $size = 'text-lg px-4 py-1';
@endphp

<span {{ $attributes->class([
    'inline-flex items-center rounded-full text-xs font-medium',
    $color,
    $size,
]) }}>
    {{ $label ?? $slot }}
</span>
