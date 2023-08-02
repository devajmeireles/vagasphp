@props([
    'text',

    'sm' => null,
    'md' => null,
    'lg' => null,
    'xl' => null,

    'icon' => 'question-mark-circle'
])

@php
    $size = 'w-4 h-4';

    if($sm) $size = 'w-2 h-2';
    if($md) $size = 'w-4 h-6';
    if($lg) $size = 'w-5 h-8';
    if($xl) $size = 'w-10 h-10';
@endphp

<x-dynamic-component component="svg.{{ $icon }}" x-tooltip="{!! $text !!}" {{ $attributes->class(['inline-flex', $size]) }} />
