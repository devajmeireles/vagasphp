@props(['filters' => [], 'authenticated' => false])

@php($collect = collect($filters))

<div class="pb-4 flex justify-end" x-data="{ open : false }">
    <p class="text-base text-primary dark:text-white font-semibold">
        @lang('Filtro')
        <x-badge gray>{{ $collect->count() + ($authenticated ? 1 : 0) }}</x-badge>
    </p>
    <div class="flex flex-shrink-0 self-center">
        <div class="relative inline-block text-left">
            <button @click="open = !open" type="button"
                    class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600" id="menu-0-button"
                    aria-expanded="false" aria-haspopup="true">
                <x-svg.chevron-up-down class="ml-2 w-5 h-5"/>
            </button>
            <div x-show="open" x-cloak
                 @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="bg-white dark:bg-gray-600 absolute right-2 z-10 mt-2 w-34 origin-top-right rounded-md shadow ring-1 ring-black ring-opacity-5 focus:outline-none"
                 role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
                <div class="px-4 py-2" role="none">
                    @auth
                        <div class="mt-2 mx-2">
                            <x-input.checkbox wire:click="authenticated"
                                              name="my"
                                              label="Minhas"
                                              :checked="$authenticated"/>
                            <hr class="mt-2 mb-4">
                        </div>
                    @endauth
                    @if (!$authenticated)
                        @foreach (\App\Enums\JobSpecification::cases() as $specification)
                            <x-input.checkbox wire:click="filter({{ $specification->value }})"
                                              wire:key="{{ uniqid() }}"
                                              :name="$specification->name"
                                              :label="$specification->name()"
                                              :checked="$collect->contains($specification->value)"/>
                        @endforeach
                    @endif
                </div>
                @if ($collect->isNotEmpty() || $authenticated)
                    <p class="mb-4 flex justify-center text-xs text-red-600 dark:text-red-400 font-semibold cursor-pointer" wire:click="clear">
                        @lang('Limpar')
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
