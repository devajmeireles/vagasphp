@props([
    'options',
    'selected' => null,
])

<div x-data="{
        tab: @if($selected) @js($selected) @else @entangle($attributes->wire('model')) @endif,
        select(tab) {
            this.tab = tab;
        },
        selected(tab) {
            return this.tab === tab;
        },
    }" x-id="['tab']" class="w-full">
    <ul x-ref="tablist"
        @keydown.right.prevent.stop="$focus.wrap().next()"
        @keydown.home.prevent.stop="$focus.first()"
        @keydown.page-up.prevent.stop="$focus.first()"
        @keydown.left.prevent.stop="$focus.wrap().prev()"
        @keydown.end.prevent.stop="$focus.last()"
        @keydown.page-down.prevent.stop="$focus.last()"
        role="tablist"
        class="-mb-px flex items-stretch">
        @foreach ($options as $tab)
            <li>
                <button id="{{ $tab }}"
                        @click="select(@js($tab))"
                        @mousedown.prevent
                        @focus="select(@js($tab))"
                        type="button"
                        class="inline-flex px-5 py-2.5 text-gray-700 rounded-tl-lg rounded-tr-lg rounded-tr-lg transition"
                        :tabindex="selected(@js($tab)) ? 0 : -1"
                        :aria-selected="selected(@js($tab))"
                        :class="selected(@js($tab)) ? 'bg-white dark:bg-gray-600 text-primary dark:text-white font-semibold' : 'bg-white dark:bg-gray-600 dark:text-gray-300 opacity-50'"
                        role="tab">
                    {{ $tab }}
                </button>
            </li>
        @endforeach
    </ul>
    <div role="tabpanels">
        {{ $slot }}
    </div>
</div>
