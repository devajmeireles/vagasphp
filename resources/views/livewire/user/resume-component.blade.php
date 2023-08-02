@php($blank = $user->resume === null)

<div>
    <div @class(['flex justify-center', 'mt-2' => $blank])>
        <div class="space-y-1 text-center">
            @if (!$blank)
                <x-button xs :href="url(sprintf('storage/resumes/%s', $user->resume))" blank>
                    <x-svg.eye class="w-4 h-4" />
                </x-button>
            @endif
            <div class="text-sm flex justify-end text-gray-600">
                <label for="file-upload" class="text-xs relative cursor-pointer rounded-md font-medium text-primary dark:text-white focus:outline-none">
                    <span>@lang('Upload de Currículo')</span>
                    <input wire:model.debounce="resume" id="file-upload" name="file" type="file" class="sr-only">
                </label>
            </div>
            <x-input.error name="resume" />
        </div>
    </div>
    <div class="flex justify-center">
        @if (!$blank)
            <x-svg.trash class="w-4 h-4 text-red-500 cursor-pointer" wire:click="remove" />
        @endif
    </div>
</div>