<div>
    @forelse ($candidacies as $candidacy)
        @php($job = $candidacy->job)
        <div class="mb-4">
            <x-job.card.candidacy :$candidacy :$job />
        </div>
    @empty
        <x-job.empty.candidacy />
    @endforelse
    <x-loading wire:loading wire:target="load" />
    @if ($candidacies?->isNotEmpty() && $candidacies->count() > 5 && $candidacies->count() >= $quantity)
        <div class="mt-2 w-full" x-data="{ intersect : false }" x-intersect="$wire.call('load')"></div>
    @endif
</div>
