<?php

namespace App\Http\Livewire\User;

use App\Enums\JobStatus;
use App\Http\Livewire\Traits\{Alert, Redirectable};
use App\Models\User;
use Illuminate\Support\Facades\{Auth, DB, Storage};
use Livewire\Component;
use Throwable;

class DestroyProfileComponent extends Component
{
    use Alert;
    use Redirectable;

    public User $user;

    protected $listeners = [
        'destroy::profile::component::destroy' => 'destroy',
        'destroy::profile::component::cancel'  => 'cancel',
    ];

    public function render(): string
    {
        return <<<'blade'
            <div class="mt-4 flex justify-end">
                <x-button danger xs wire:click="confirmingBeforeDestroy">
                    Excluir
                </x-button>
            </div>
        blade;
    }

    public function confirmingBeforeDestroy(): void
    {
        $this->bag([
            'confirm'     => 'destroy::profile::component::destroy',
            'cancel'      => 'destroy::profile::component::cancel',
            'dismissable' => false,
        ])->confirm(__("Você tem certeza que deseja fazer isso?"), __('Sério?'));
    }

    public function destroy(): bool
    {
        if (
            $this->user
                ->candidacy()
                ->count() > 0
        ) {
            return $this->error(__("Cancele todas as suas candidaturas."));
        }

        if (
            $this->user
                ->job()
                ->whereIn('status', [
                    JobStatus::Actived,
                    JobStatus::Review,
                ])
                ->exists()
        ) {
            return $this->error(__("Encerre os anúncios ativos ou em revisão."));
        }

        Auth::guard('web')->logout();

        try {
            DB::transaction(function () {
                Storage::delete(sprintf('public/resumes/%s', $this->user?->resume));
                $this->user->delete();
            });

            $this->redirecting(route('index'));

            return $this->success(__("Esperamos te ver em breve ... Até mais!"), __('Vazou!'));
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error();
    }

    public function cancel(): bool
    {
        return $this->success(__("Que susto. Seu perfil está seguro!"), __('Ufa!'));
    }
}
