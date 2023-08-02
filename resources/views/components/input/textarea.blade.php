@props([
    'label' => null,
    'resize' => false,
    'rows' => 4,
])

@if ($label)
    <x-input.label :$label />
@endif

@php
    $resize = $resize ? 'resize' : 'resize-none';
@endphp

<textarea rows="{{ $rows }}" {{
    $attributes->class([
        'dark:bg-gray-500 dark:text-white dark:border-gray-500',
        'mt-2 px-3 py-1 block w-full rounded-md text-gray-600 shadow-sm border border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 custom-scrollbar',
        $resize,
    ]) }}>{{ $slot }}</textarea>

<x-input.error name="{{ $attributes->get('name', $attributes->whereStartsWith('wire:model')->first()) }}" />
