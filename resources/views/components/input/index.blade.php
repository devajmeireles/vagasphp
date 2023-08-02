@props([
    'label' => null,
    'disabled' => false
])

@if ($label)
    <x-input.label :$label />
@endif

<input @disabled($disabled) {!! $attributes->class([
    'dark:bg-gray-500 dark:text-white dark:placeholder:text-white dark:placeholder:opacity-50',
    'mt-2 px-3 py-1 block w-full rounded-md text-gray-600 shadow-sm border border-gray-300 dark:border-gray-500 focus:outline-none'
]) !!}>

<x-input.error name="{{ $attributes->get('name', $attributes->whereStartsWith('wire:model')->first()) }}" />
