@props(['results', 'selected'])

<x-searchable label="Requerimentos" entangle="searching" :placeholder="__('Digite para procurar...')" :$results>
    @forelse($results ?? [] as $requirement)
        <x-searchable.option wire:click="select({{ json_encode($requirement) }})" wire:key="{{ uniqid() }}">
            {{ $requirement['name'] }}
        </x-searchable.option>
    @empty
        <x-searchable.empty/>
    @endforelse
</x-searchable>
@if (!empty($selected))
    <div class="mt-2 space-y-2">
        @foreach($selected as $requirement)
            <x-badge xs gray>
                {{ $requirement['name'] }}
                <x-svg.x-mark class="ml-2 w-4 h-4 text-red-500"
                              wire:click="unselect({{ $requirement['id'] }})"
                              wire:key="{{ uniqid() }}"/>
            </x-badge>
        @endforeach
    </div>
@endif

