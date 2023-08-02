@props([
    'label' => null,

    'href'  => null,
    'blank' => null,

    'xs' => null,
    'sm' => null,
    'md' => null,
    'lg' => null,

    'block' => null,

    'white'  => null,
    'green'  => null,
    'red'    => null,
    'yellow' => null,
    'blue'   => null,
    'gray'   => null,
])

@php
    $type = 'primary';

    if ($red)    $type = 'red';
    if ($green)  $type = 'green';
    if ($yellow) $type = 'yellow';
    if ($blue)   $type = 'blue';
    if ($gray)   $type = 'gray';
    if ($white)  $type = 'white';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->whereDoesntStartWith('wire')->class([
        'background-transparent inline-flex justify-center rounded-md items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium text-center focus:outline-none',
        'w-full'                         => $block !== null,
        'px-1 py-1'                      => $xs !== null,
        'px-2 py-1'                      => $sm !== null,
        'px-6 py-2'                      => $md !== null,
        'px-8 py-4'                      => $lg !== null,
        'px-4 py-2'                      => $xs === null && $sm === null && $md === null && $lg === null,
        'text-green-700'                 => $type === 'green',
        'text-red-700 dark:text-red-400' => $type === 'red',
        'text-yellow-700'                => $type === 'yellow',
        'text-blue-700'                  => $type === 'blue',
        'text-gray-700'                  => $type === 'gray',
        'text-white'                     => $type === 'white',
        'text-primary dark:text-white'   => $type === 'primary',
    ]) }} {{ $attributes->whereStartsWith('wire') }} @if ($blank) target="_blank" @endif>
        {{ $label ?? $slot }}
    </a>
@endif

@if (!$href)
    <button
        {{ $attributes->whereDoesntStartWith('wire')->class([
            'background-transparent inline-flex justify-center rounded-md items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium text-center focus:outline-none',
            'w-full'                         => $block !== null,
            'px-1 py-1'                      => $xs !== null,
            'px-2 py-1'                      => $sm !== null,
            'px-6 py-2'                      => $md !== null,
            'px-8 py-4'                      => $lg !== null,
            'px-4 py-2'                      => $xs === null && $sm === null && $md === null && $lg === null,
            'text-green-700'                 => $type === 'green',
            'text-red-700 dark:text-red-400' => $type === 'red',
            'text-yellow-700'                => $type === 'yellow',
            'text-blue-700'                  => $type === 'blue',
            'text-gray-700'                  => $type === 'gray',
            'text-white'                     => $type === 'white',
            'text-primary dark:text-white'   => $type === 'primary',
        ]) }} {{ $attributes->whereStartsWith('wire') }}>
        {{ $label ?? $slot }}
    </button>
@endif
