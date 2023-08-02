@props([
    'section',
])

<section
    x-show="selected(@js($section))"
    :aria-labelledby="{{ $section }}"
    role="tabpanel"
    class="bg-white dark:bg-gray-600 rounded-b-md rounded-tr-md p-6">
    {{ $slot }}
</section>
