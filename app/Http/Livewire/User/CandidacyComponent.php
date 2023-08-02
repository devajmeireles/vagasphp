<?php

namespace App\Http\Livewire\User;

use App\Http\Livewire\Traits\Alert;
use App\Models\{Job, User};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class CandidacyComponent extends Component
{
    use Alert;

    public User $user;

    public int $quantity = 10;

    protected $listeners = [
        'candidacy::component::cancel'   => 'cancel',
        'candidacy::component::preserve' => 'preserve',
    ];

    public function render(): View
    {
        $candidacies = $this->data();

        return view('livewire.user.candidacy-component', compact('candidacies'));
    }

    private function data(): Collection
    {
        return $this->user
            ->candidacy()
            ->with('job')
            ->latest()
            ->take($this->quantity)
            ->get();
    }

    public function load(): void
    {
        $this->quantity += 10;
    }

    public function confirmingBeforeCancel(Job $job): void
    {
        $this->bag([
            'confirm'     => 'candidacy::component::cancel',
            'cancel'      => 'candidacy::component::preserve',
            'append'      => ['job' => $job->id],
            'dismissable' => false,
        ])->confirm();
    }

    public function cancel(array $appended): bool
    {
        //TODO: send notification to the job owner

        try {
            DB::transaction(
                fn () => $this->user
                ->candidacy()
                ->where('job_id', '=', $appended['job'])
                ->delete()
            );
        } catch (Throwable $e) {
            report($e);

            return $this->error();
        }

        return $this->success();
    }

    public function preserve(): bool
    {
        return $this->success(__("Sua candidatura está preservada. Boa sorte!"));
    }
}
