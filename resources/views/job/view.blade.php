<x-app-layout>
    @php /** @var \App\Models\Job $job */ @endphp
    <div x-data="{ edit : false, expiration : false }">
        <div class="mb-2 flex justify-between items-center">
            <x-back-button :route="route('index')" />
            @can ('update', $job)
                <livewire:job.manipulate-component :job="$job" />
            @endcan
        </div>
        @can ('candidacy', $job)
            <livewire:job.candidacy-component :job="$job" />
        @elseif ($job->isActive() && !$job->user()->is(user()))
            <div class="mb-2">
                <x-alert yellow center>
                    @lang('Gostou deste anúncio? <b>Faça login para candidatar-se!</b>')
                </x-alert>
            </div>
        @endcan
        @if ($job->created_at->lessThan(now()->subMonth()))
            <div class="mb-4">
                <x-alert yellow center>
                    @lang('<b>ATENÇÃO!</b> Esta vaga foi criada há mais de um mês atrás.')
                </x-alert>
            </div>
        @endif
        <x-card>
            <div class="flex justify-end">
                <x-tooltip icon="specification.{{ $job->specification->icon() }}" :text="__('Requisito Principal')" class="w-10 h-10"/>
            </div>
            <div class="mb-2">
                <h1 class="text-2xl text-primary dark:text-gray-300 font-bold">{{ $job->title }}</h1>
                <p class="text-xs text-gray-700 dark:text-white">@lang('Criado:') <b class="text-red-500 dark:text-yellow-500">{{ $job->created_at->format('d/m/Y H:i') }}</b></p>
                @if ($job->description)
                    <h1 class="mt-2 text-2xl text-primary dark:text-gray-300 font-bold">@lang('Descrição')</h1>
                    <div class="dark:text-white">
                        {!! str($job->description)->markdown() !!}
                    </div>
                @endif
            </div>
            <div class="mb-2">
                <h1 class="text-2xl text-primary dark:text-gray-300 font-bold">@lang('Empresa')</h1>
                <p class="mt-2 text-gray-700 dark:text-white">
                    {{ $job->company->get('name') }}
                    @if (($site = $job->company->get('site')) !== null)
                        <a href="{{ $site }}" target="_blank">
                            <x-svg.link class="inline-flex w-5 h-5 text-gray-700" />
                        </a>
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <h1 class="text-2xl text-primary dark:text-gray-300 font-bold">@lang('Características')</h1>
                <ul class="mt-2 text-gray-700 dark:text-white list-disc list-inside">
                    @if ($job->type)
                        <li>@lang('Tipo:') <b>{{ $job->type->translate() }}</b></li>
                    @endif
                    @if ($job->model)
                        <li>@lang('Modelo de Atuação:') <b>{{ $job->model->translate() }}</b></li>
                    @endif
                    @if ($job->modality)
                        <li>@lang('Localização:') <b>{{ $job->modality->translate() }}</b></li>
                    @endif
                    @if ($job->remuneration)
                        <li>@lang('Remuneração:') <b>{{ $job->remuneration() }}</b></li>
                    @endif
                    @if($job->requirement?->isNotEmpty())
                        <li>@lang('Requerimentos:')
                            @foreach($job->requirement as $label)
                                <x-badge :$label />
                            @endforeach
                        </li>
                    @endif
                </ul>
            </div>
            <livewire:job.expire-component :job="$job" />
        </x-card>
    </div>
</x-app-layout>
