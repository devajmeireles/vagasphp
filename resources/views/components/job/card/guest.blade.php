@props(['job'])

@php
    /** @var \App\Models\Job $job */
    $redirectable = $job->redirectable();
    $link         = $redirectable && $job->link ? $job->link : route('job.view', $job);
@endphp

<x-job.card.body :$job>
    <x-slot:body>
        <div class="min-w-0 flex-1">
            <a href="{{ $link }}"
               @if ($redirectable) target="_blank" @endif
               class="text-xl text-primary dark:text-white font-medium text-gray-900">
                {{ $job->title }}
                @if ($redirectable)
                    <x-svg.link class="inline-flex w-4 h-4 text-gray-700 dark:text-white" />
                @endif
            </a>
            <p class="text-sm text-gray-500 dark:text-white">Criado: {{ $job->created_at->format('d/m/Y') }}</p>
            <div class="flex items-center space-x-1">
                <x-svg.eye class="inline-block w-4 h-4 text-primary-600 dark:text-white"/>
                <p class="text-xs text-gray-700 dark:text-white">{{ $job->result }} </p>
            </div>
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
