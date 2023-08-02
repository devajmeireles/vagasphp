@props([
    'label' => null,
    'entangle',
    'results',
    'placeholder' => null,
])

<div class="relative">
    <x-input
        :label="$label"
        :placeholder="$placeholder"
        wire:model.debounce.500ms="{{ $entangle }}" />
    @if (!empty($results))
        <ul class="relative z-10 max-h-60 w-full overflow-auto py-1 rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out text-gray-700 dark:text-white mt-1 bg-gray-100 dark:bg-gray-500 border border-gray-200 dark:border-gray-500 divide-y divide-gray-200 dark:divide-gray-400 custom-scrollbar" role="listbox">
            {{ $slot }}
        </ul>
    @endif
</div>
