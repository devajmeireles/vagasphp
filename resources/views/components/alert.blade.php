@props([
    'green'  => null,
    'red'    => null,
    'yellow' => null,
    'blue'   => null,
    'gray'   => null,

    'dismissable' => null,

    'center' => null,
    'icon'   => null,
])

@php
    $type = 'primary';

    if ($red)    $type = 'red';
    if ($green)  $type = 'green';
    if ($yellow) $type = 'yellow';
    if ($blue)   $type = 'blue';
    if ($gray)   $type = 'gray';
@endphp

<div @class([
        'border-l-4 rounded-md p-4',
        'border-green-400 bg-green-200'      => $type === 'green',
        'border-red-400 bg-red-200'          => $type === 'red',
        'border-yellow-400 bg-yellow-200'    => $type === 'yellow',
        'border-blue-400 bg-blue-200'        => $type === 'blue',
        'border-gray-400 bg-gray-200'        => $type === 'gray',
        'border-primary-200 bg-primary-400'  => $type === 'primary',
    ]) @if ($dismissable) x-data="{ show : true }" x-cloak x-show="show" @endif>
    <div @class([
            'flex',
            'justify-center' => $center !== null,
        ])>
        <div class="flex-shrink-0">
            @if ($icon)
                <x-dynamic-component component="svg.{{ $icon }}" @class([
                    'h-5 w-5',
                    'text-primary-200' => $type === 'primary',
                    'text-green-600'   => $type === 'green',
                    'text-red-600'     => $type === 'red',
                    'text-yellow-600'  => $type === 'yellow',
                    'text-blue-600'    => $type === 'blue',
                    'text-gray-600'    => $type === 'gray',
                ]) />
            @endif
        </div>
        <div class="ml-2">
            <p @class([
                'text-sm',
                'text-white'      => $type === 'primary',
                'text-green-700'  => $type === 'green',
                'text-red-700'    => $type === 'red',
                'text-yellow-700' => $type === 'yellow',
                'text-blue-700'   => $type === 'blue',
                'text-gray-700'   => $type === 'gray',
            ])>
                {{ $slot }}
            </p>
        </div>
        @if ($dismissable)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button"
                            @click="show = !show"
                            @class([
                                'inline-flex p-1.5',
                                'text-primary-500' => $type === 'primary',
                                'text-green-500'   => $type === 'green',
                                'text-red-500'     => $type === 'red',
                                'text-yellow-500'  => $type === 'yellow',
                                'text-blue-500'    => $type === 'blue',
                                'text-gray-500'    => $type === 'gray',
                            ])>
                        <span class="sr-only">Dismiss</span>
                        <x-svg.x-mark class="h-4 w-4"/>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
