@props([
    'route',
    'target' => null,
    'icon' => null,
    'label' => null
])

<a role="menuitem"
   href="{{ $route }}"
   tabindex="-1" id="menu-0-item-0"
   @if ($target) target="{{ $target }}" @endif
   {{ $attributes->merge(['class' => 'text-gray-700 flex px-4 py-2 text-sm hover:bg-primary-50']) }}>
    @if ($icon)
        <x-dynamic-component
            component="svg.{{ $icon }}"
            class="mr-3 w-5 h-5"/>
    @endif
    <span>{{ $label ?? $slot }}</span>
</a>
