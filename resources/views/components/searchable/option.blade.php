@props(['label' => null])

<li {{ $attributes->merge(['class' => 'relative select-none py-2 pl-3 pr-9 text-dark-primary dark:text-white cursor-pointer']) }}>
    <div class="flex items-center">
        <span class="ml-2 truncate">
            {{ $label ?? $slot }}
        </span>
    </div>
</li>
