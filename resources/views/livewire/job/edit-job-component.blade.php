@php($actived = $job->isActive())

<div x-data="{ remuneration : @entangle('job.remuneration.type') }">
    <div class="mb-4">
        <x-back-button :route="route('job.view', $job)" />
    </div>
    <x-card>
        @if (!$actived)
        <div class="mb-4">
            <x-alert yellow>
                @lang('<b>ATENÇÃO!</b> Você não pode editar este anúncio porque ele não está ativo.')
            </x-alert>
        </div>
        @endif
        <div class="grid grid-cols-6 gap-2">
            <div class="col-span-full">
                <x-input label="Titulo" wire:model.defer="job.title"/>
            </div>
            <div class="col-span-full">
                <x-input.textarea :rows="10" label="Descrição" wire:model.debounce.2000="job.description"/>
                <div class="mt-2 ml-2">
                    <p class="text-xs text-gray-700 dark:text-white">@lang('A descrição aceita formatações em markdown.')</p>
                </div>
                @if (!empty($description = data_get($job, 'description')))
                    <div class="mb-4">
                        <p class="font-medium text-lg text-red-500 ml-2">@lang('Preview:')</p>
                        <div class="p-4 rounded-md bg-primary-200 ml-4">
                            {!! str($description)->markdown() !!}
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-span-3">
                <x-input label="Empresa" wire:model.defer="job.company.name"/>
            </div>
            <div class="col-span-3">
                <x-input label="Site da Empresa" wire:model.defer="job.company.site"/>
            </div>
            <div class="col-span-full">
                <p class="font-medium text-sm text-gray-700 dark:text-white ml-2">@lang('Modelo')</p>
                <div class="ml-2 mt-2 grid">
                    @foreach (\App\Enums\JobModel::cases() as $model)
                        <x-input.radiobox name="model" :value="$model->value" wire:model.defer="job.model" :label="$model->translate()"/>
                    @endforeach
                </div>
                <x-input.error name="job.model" />
            </div>
            <div class="col-span-full">
                <p class="font-medium text-sm text-gray-700 dark:text-white ml-2">@lang('Tipo')</p>
                <div class="ml-2 mt-2 grid">
                    @foreach (\App\Enums\JobTypes::cases() as $type)
                        <x-input.radiobox name="type" :value="$type->value" wire:model.defer="job.type" :label="$type->translate()"/>
                    @endforeach
                </div>
                <x-input.error name="job.type" />
            </div>
            <div class="col-span-full">
                <p class="font-medium text-sm text-gray-700 dark:text-white ml-2">
                    @lang('Especificação')
                    <x-tooltip lg class="text-red-500" text="Requisito Principal"/>
                </p>
                <div class="ml-2 mt-2 grid">
                    @foreach (\App\Enums\JobSpecification::cases() as $specification)
                        <x-input.radiobox name="specification" :value="$specification->value" wire:model.defer="job.specification" :label="$specification->name()"/>
                    @endforeach
                </div>
                <x-input.error name="job.specification" />
            </div>
            <div class="col-span-full">
                <p class="font-medium text-sm text-gray-700 dark:text-white ml-2">@lang('Modalidade')</p>
                <div class="ml-2 mt-2 grid">
                    @foreach (\App\Enums\JobModality::cases() as $modality)
                        <x-input.radiobox name="modality" :value="$modality->value" wire:model.defer="job.modality" :label="$modality->translate()"/>
                    @endforeach
                </div>
                <x-input.error name="job.modality" />
            </div>
            <div class="col-span-full">
                <p class="font-medium text-sm text-gray-700 dark:text-white ml-2">@lang('Remuneração')</p>
                <div class="ml-2 mt-2 grid">
                    <x-input.radiobox name="remuneration" value="fix" wire:model="job.remuneration.type" label="Salário Fixo"/>
                    <x-input.radiobox name="remuneration" value="timing" wire:model="job.remuneration.type" label="Hora Produzida"/>
                    <x-input.radiobox name="remuneration" value="interview" wire:model="job.remuneration.type" label="Informado Posteriormente (Entrevista)"/>
                    <div class="mt-2" x-show="['fix', 'timing'].includes(remuneration)">
                        <x-input label="Valor" wire:model.defer="job.remuneration.value"/>
                    </div>
                </div>
                <x-input.error name="job.remuneration.type" />
            </div>
            <div class="col-span-full">
                <x-job.requirements :$results :$selected />
            </div>
            <div class="col-span-full">
                <p class="font-medium text-sm text-gray-700 dark:text-white ml-2">@lang('Configurações')</p>
                <div class="ml-2 mt-2 grid">
                    <x-input.checkbox name="configuration.subscribe.resume"
                                      wire:model.defer="job.configuration.resume"
                                      label="Permitir candidaturas com envio de currículo"/>
                    <x-input.checkbox name="configuration.subscribe.github"
                                      wire:model.defer="job.configuration.github"
                                      label="Permitir candidaturas com envio de GitHub"/>
                </div>
            </div>
            <div class="col-span-full" x-transition>
                <x-input label="Notificação" :placeholder="__('URL Webhook do Slack ou E-mail')" wire:model.defer="job.notification"/>
                <div class="mt-2">
                    <x-alert blue>
                        @lang('Quando uma candidatura ocorrer você pode ser notificado através de um <b>webhook para um canal do Slack ou via e-mail.</b>')
                    </x-alert>
                </div>
            </div>
        </div>
        @if ($actived)
            <div class="mt-4 flex justify-end">
                <x-button wire:click="confirmingBeforeUpdate"
                          wire:loading.attr="disabled"
                          wire:target="confirmingBeforeUpdate"
                          x-cloak>
                    @lang('Salvar')
                </x-button>
            </div>
        @endif
    </x-card>
</div>
