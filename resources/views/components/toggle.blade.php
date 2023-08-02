@props([
    'label',
    'trigger',
])

<button type="button"
        {{ $attributes->merge(['class' => 'mt-3 inline-flex flex-shrink-0 h-5 w-10 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200']) }}
        role="switch"
        @click="{{ $trigger }} = !{{ $trigger }}"
        :class="{ 'bg-primary-600 dark:bg-gray-700': {{ $trigger }}, 'bg-gray-200 dark:bg-gray-400': !({{ $trigger }}) }"
        x-cloak>
    <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-5"
          :class="{ 'translate-x-5': {{ $trigger }}, 'translate-x-0': !{{ $trigger }} }"> {{ $label ?? '' }}
    </span>
</button>
