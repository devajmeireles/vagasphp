@props(['candidacy', 'job'])

@php
    /** @var \App\Models\Candidacy $candidacy */
    /** @var \App\Models\Job $job */

    $redirectable = $job->redirectable();
    $link         = $redirectable && $job->link ? $job->link : route('job.view', $job);
@endphp

<x-job.card.body :$job>
    <x-slot:header>
        <div class="flex justify-end">
            <x-svg.trash class="w-5 h-5 text-red-500 cursor-pointer" wire:click="confirmingBeforeCancel({{ json_encode($job->id) }})" />
        </div>
    </x-slot:header>
    <x-slot:body>
        <div class="min-w-0 flex-1">
            <a href="{{ $link }}"
               class="text-xl text-primary dark:text-white font-medium text-gray-900"
               @if ($redirectable) target="_blank" @endif>
                {{ $job->title }}
            </a>
            <p class="text-sm text-gray-500 dark:text-white">@lang('Candidatura Enviada: :date', ['date' => $candidacy->created_at->format('d/m/Y H:i')])</p>
            @if ($job->requirement?->isNotEmpty())
                <div>
                    @foreach($job->requirement as $label)
                        <x-badge :$label />
                    @endforeach
                </div>
            @endif
        </div>
    </x-slot:body>
</x-job.card.body>
