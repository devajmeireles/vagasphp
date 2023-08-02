@props(['header' => null, 'body' => null, 'footer' => null,'job'])

@php /** @var \App\Models\Job $job */ @endphp

<div class="bg-white dark:bg-gray-700 rounded-md shadow px-4 py-5 sm:px-6" id="{{ uniqid() }}">
    {{ $header }}
    <div class="flex space-x-3">
        <div class="flex items-center">
            <x-dynamic-component
                component="svg.specification.{{ $job->specification->icon() }}"
                class="w-8 h-8"/>
        </div>
        {{ $body ?? $slot }}
    </div>
    {{ $footer }}
</div>
