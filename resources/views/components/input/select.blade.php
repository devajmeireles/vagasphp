@props([

    'label' => null,

    'id' => null,
    'name' => null,

    'selected' => null,
    'options'  => null,
])

@if ($label)
    <x-input.label :$label />
@endif

<select {{ $attributes->merge(['class' => 'mt-2 appearance-none w-full text-gray-500 text-sm rounded-md border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 dark:bg-gray-500 dark:border-gray-500 dark:text-white']) }}
        @if($id) id="{{ $id }}" @endif
        @if($name) name="{{ $name }}" @endif>
    @if(is_array($options))
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @selected($selected && $option === $selected)>{!! $option !!}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
