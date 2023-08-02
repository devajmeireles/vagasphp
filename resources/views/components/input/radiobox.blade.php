@props([

    'name',
    'label',

    'checked' => false,
    'disabled' => false
])

<label for="{{ $name }}" class="inline-flex items-center">
    <input id="{{ uniqid() }}"
           type="radio" {{ $attributes->merge(['class' => 'rounded-full border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition']) }}
           name="{{ $name }}" @disabled($disabled) @checked($checked)>
    <span class="ml-2 text-sm text-gray-600 dark:text-white">{{ $label }}</span>
</label>
