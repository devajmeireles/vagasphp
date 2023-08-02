@props(['route'])

<div>
    <x-button xs :href="$route">
        <x-svg.arrow-left class="w-4 h-4 mr-2" />
        @lang('Voltar')
    </x-button>
</div>
