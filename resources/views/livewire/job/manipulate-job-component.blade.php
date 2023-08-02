@php
    $inactive = $job->isInactive();
    $active   = $job->isActive();
    $review   = $job->isReview();
@endphp

<div class="flex justify-end" x-data="{ manipulation : false, expiration : false }">
    <x-button xs @click="manipulation = !manipulation">
        <x-svg.pencil class="h-4 w-4" />
    </x-button>
    <x-modal :title="__('Manipulação de Anúncio')" trigger="manipulation">
        <div class="mt-5 grid grid-cols-6 gap-2">
            @if ($active)
                <div class="col-span-3">
                    <x-button block green :href="route('job.edit', $job)">
                        <x-svg.pencil class="w-4 h-4 mr-2" />
                        @lang('Editar Anúncio')
                    </x-button>
                </div>
            @endif
            @if (!$inactive)
                <div @class(['col-span-3' => $active, 'col-span-full' => $review])>
                    <x-button block yellow @click="manipulation = false; expiration = !expiration">
                        <x-svg.check class="w-5 h-5 mr-2" />
                        @lang('Expirar Anúncio')
                    </x-button>
                </div>
            @endif
            @if ($inactive)
                <div @class(['col-span-3' => !$inactive, 'col-span-full' => $inactive])>
                    <x-button block red wire:click="destroy">
                        <x-svg.x-mark class="w-5 h-5 mr-2" />
                        @lang('Excluir Anúncio')
                    </x-button>
                </div>
            @endif
        </div>
    </x-modal>
    <livewire:job.expire-component :job="$job" :home="$home" />
</div>
