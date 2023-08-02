<?php

namespace App\Http\Livewire\Job;

use App\Events\Job\JobCandidacy;
use App\Http\Livewire\Traits\Alert;
use App\Models\{Job, User};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, DB};
use Livewire\Component;
use Throwable;

class CandidacyComponent extends Component
{
    use Alert;

    public Job $job;

    public ?User $user = null;

    public bool $modal = false;

    public bool $terms = false;

    public array $candidacy = [];

    protected $validationAttributes = [
        'candidacy.phone' => 'Telefone',
    ];

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render(): View
    {
        data_set($this->candidacy, 'phone', $this->user->phone);

        return view('livewire.job.candidacy-job-component');
    }

    public function rules(): array
    {
        return [
            'candidacy.phone' => [
                'required',
                'string',
                'min:10',
                'max:15',
            ],
        ];
    }

    public function submit(): bool
    {
        $candidacy = $this->candidacy;
        $this->reset('modal', 'terms', 'candidacy');

        if (
            $this->job
                ->user()
                ->is($this->user)
        ) {
            return $this->error(__("Você não pode se candidatar a esta vaga!"));
        }

        if (
            $this->job
                ->candidacy()
                ->where('user_id', '=', $this->user->id)
                ->exists()
        ) {
            return $this->error(__("Você já é um candidato para esta vaga."));
        }

        try {
            DB::transaction(function () use ($candidacy) {
                $this->user
                    ->update([
                        'phone' => data_get($candidacy, 'phone'),
                    ]);

                $this->job
                    ->candidacy()
                    ->create([
                        'user_id' => $this->user->id,
                    ]);
            });

            $this->user->refresh();
            event(new JobCandidacy($this->job, $this->user));

            return $this->success(__('Candidatura Enviada. Boa sorte!'));
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error();
    }
}
