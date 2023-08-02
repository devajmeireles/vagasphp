@props(['job'])

@php
    /** @var \App\Models\Job $job */
    $inactive = $job->isInactive();
@endphp

<x-job.card.body :$job>
    <x-slot:header>
        <div class="flex justify-end">
            <x-badge sm :type="$job->status->badge()" :label="$job->status->translate()"/>
        </div>
    </x-slot:header>
    <x-slot:body>
        <div class="min-w-0 flex-1">
            @if (!$inactive)
                <div class="flex justify-start">
                    <a href="{{ route('job.view', $job) }}">
                        @endif
                        <h3 class="text-xl text-primary dark:text-white font-medium">{{ $job->title }}</h3>
                        @if (!$inactive)
                    </a>
                </div>
            @endif
            <div class="bg-primary-100 dark:bg-gray-600 p-4 mb-2 rounded-md">
                <p class="text-sm text-gray-500 dark:text-white">Criado: {{ $job->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-sm text-gray-500 dark:text-white">Atualizado: {{ $job->updated_at->format('d/m/Y H:i') }}</p>
                @if ($inactive)
                    <p class="text-sm text-red-500 dark:text-red-400 font-semibold">
                        @lang($job->status->translate()): {{ $job->deleted_at->format('d/m/Y H:i') }}
                    </p>
                @endif
            </div>
            <div class="flex items-center space-x-1">
                <x-svg.eye class="inline-block w-4 h-4 text-primary-600 dark:text-white"/>
                <p class="text-xs text-gray-700 dark:text-white">
                    @lang($job->result . ' :result', ['result' => $job->detailable() ? 'Acessos' : 'Clicks'])
                </p>
            </div>
        </div>
    </x-slot:body>
    <x-slot:footer>
        @if ($job->link && $job->detailable() && !$inactive)
            <p class="mt-4 text-sm text-primary font-semibold dark:text-white">URL Encurtada: {{ url($job->link) }}</p>
        @endif
        <livewire:job.manipulate-component :job="$job" :home="true" :wire:key="uniqid()" />
    </x-slot:footer>
</x-job.card.body>
