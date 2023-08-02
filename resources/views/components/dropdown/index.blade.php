<div class="flex flex-shrink-0 self-center" x-data="{ open : false }" x-cloak id="{{ uniqid() }}">
    <div class="relative inline-block text-left">
        <div>
            <button
                id="menu-0-button"
                aria-haspopup="true"
                aria-expanded="false"
                @click="open = !open" type="button"
                class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600">
                <x-svg.ellipsis-vertical class="h-5 w-5"/>
            </button>
        </div>
        <div x-show="open"
             @click.outside="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-2 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow ring-1 ring-black ring-opacity-5 focus:outline-none"
             role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
            <div class="py-1" role="none">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
