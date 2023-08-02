<div>
    <div class="flex justify-end items-center" x-data="{
            remote : @entangle('remote'),
            contract : @entangle('contract')
        }" x-cloak>
        @guest
            <x-job.remote />
            <x-job.contract />
        @endguest
        <x-job.filter :$filters :$authenticated />
        @auth
            <x-job.new :route="route('job.create')" />
        @endauth
    </div>
    <div class="space-y-2">
        @forelse ($jobs as $job)
            @can ('update', $job)
                <x-job.card.authenticated :$job />
            @else
                <x-job.card.guest :$job />
            @endcan
        @empty
            <x-job.empty.job />
        @endforelse
    </div>
    <x-loading wire:loading wire:target="load" />
    @if ($jobs?->isNotEmpty() && $jobs->count() > 5 && $jobs->count() >= $quantity)
        <div class="mt-2 w-full" x-data="{ intersect : false }" x-intersect="$wire.call('load')"></div>
    @endif
</div>
