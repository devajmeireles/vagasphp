@props([
    'label'  => null,
    'button' => true,

    'href'  => null,
    'blank' => null,

    'xs' => null,
    'sm' => null,
    'md' => null,
    'lg' => null,

    'block'  => null,

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
    <a href="{{ $href ?? '#' }}"
        {{ $attributes->whereDoesntStartWith('wire')->class([
            'inline-flex rounded-md justify-center items-center border border-transparent shadow-sm text-sm text-white font-medium text-center focus:outline-none',
            'w-full'                         => $block !== null,
            'px-1 py-1'                      => $xs !== null,
            'px-2 py-1'                      => $sm !== null,
            'px-6 py-2'                      => $md !== null,
            'px-8 py-4'                      => $lg !== null,
            'px-4 py-2'                      => $xs === null && $sm === null && $md === null && $lg === null,
            'bg-green-600'                   => $type === 'green',
            'bg-red-600 dark:bg-red-500'     => $type === 'red',
            'bg-yellow-600'                  => $type === 'yellow',
            'bg-blue-600'                    => $type === 'blue',
            'bg-gray-600'                    => $type === 'gray',
            'bg-white'                       => $type === 'white',
            'bg-primary-600 dark:text-white' => $type === 'primary',
        ]) }} {{ $attributes->whereStartsWith('wire') }} @if ($blank) target="_blank" @endif>
        {{ $label ?? $slot }}
    </a>
@endif

@if ($button && !$href)
    <button
        {{ $attributes->whereDoesntStartWith('wire')->class([
            'inline-flex rounded-md justify-center items-center border border-transparent shadow-sm text-sm text-white font-medium text-center focus:outline-none',
            'w-full'                         => $block !== null,
            'px-1 py-1'                      => $xs !== null,
            'px-2 py-1'                      => $sm !== null,
            'px-6 py-2'                      => $md !== null,
            'px-8 py-4'                      => $lg !== null,
            'px-4 py-2'                      => $xs === null && $sm === null && $md === null && $lg === null,
            'bg-green-600'                   => $type === 'green',
            'bg-red-600 dark:bg-red-500'     => $type === 'red',
            'bg-yellow-600'                  => $type === 'yellow',
            'bg-blue-600'                    => $type === 'blue',
            'bg-gray-600'                    => $type === 'gray',
            'bg-white'                       => $type === 'white',
            'bg-primary-600 dark:text-white' => $type === 'primary',
        ]) }} {{ $attributes->whereStartsWith('wire') }}>
        {{ $label ?? $slot }}
    </button>
@endif
