@props([

    'name',
    'label',

    'checked' => false,
    'disabled' => false
])

<label for="{{ $name }}" class="inline-flex items-center">
    <input
        id="{{ uniqid() }}"
        type="checkbox" {{ $attributes->merge(['class' => 'rounded-md border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition']) }}
        name="{{ $name }}" @disabled($disabled) @checked($checked)>
    <span class="ml-2 text-sm text-gray-600 dark:text-white">@lang($label)</span>
</label>
