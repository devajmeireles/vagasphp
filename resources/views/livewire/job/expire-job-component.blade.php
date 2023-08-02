<div x-data @close="expiration = !expiration">
    <x-modal :title="__('Encerramento de Anúncio')" trigger="expiration">
        <p class="mt-4 text-gray-700 dark:text-white">@lang('O que lhe levou a tomar essa decisão?')</p>
        <x-input.select wire:model="reason">
            <option value="" selected>@lang('- Selecione uma opção')</option>
            <option>@lang('A vaga foi preenchida')</option>
            <option>@lang('A vaga não está mais disponível')</option>
            <option>@lang('O processo seletivo foi concluído')</option>
            <option>@lang('O anúncio não é mais necessário')</option>
        </x-input.select>
        <x-input.error name="reason" />
        <x-slot:footer>
            <x-button red
                      wire:click="expire"
                      wire:loading.attr="disabled"
                      wire:target="expire"
                      :disabled="blank($reason)">
                @lang('Encerrar')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
