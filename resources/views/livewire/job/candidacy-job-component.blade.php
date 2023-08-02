<div class="mb-2" x-data="{
        modal : @entangle('modal').defer,
        terms : @entangle('terms').defer
    }">
    <x-button green block @click="modal = !modal">
        @lang('CANDIDATE-SE')
    </x-button>
    <x-modal :title="__('Nova Candidatura')" trigger="modal">
        <div>
            <div class="my-2 flex justify-center">
                <x-input.checkbox name="terms" id="terms" wire:model="terms" :label="__('Estou ciente e de acordo com os Termos de Uso')" />
            </div>
            <div class="grid grid-cols-6 gap-2" x-show="terms" x-transition>
                <div class="col-span-full">
                    <x-input name="candidacy.phone" id="phone" wire:model.defer="candidacy.phone" x-mask="(99) 99999-9999" :label="__('Telefone')" />
                    <x-tooltip text="Dica: use seu telefone do WhatsApp" class="text-red-500" />
                </div>
            </div>
            @if ((bool) $job->configuration->get('resume') === true && $user->resume === null)
                <div class="mt-2 col-span-full">
                    <x-alert yellow>
                        @lang('<b>Este anúncio aceita envio de currículo!</b> Anexe um currículo ao seu perfil para ter mais chances de concorrer a esta vaga.')
                    </x-alert>
                </div>
            @endif
        </div>
        <x-slot:footer>
            <x-button green wire:click="submit" :disabled="!$terms">
                <x-svg.check class="w-5 h-5 mr-2" />
                @lang('Enviar')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
