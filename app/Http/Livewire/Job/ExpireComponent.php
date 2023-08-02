<?php

namespace App\Http\Livewire\Job;

use App\Enums\{JobResult, JobStatus};
use App\Http\Livewire\Traits\{Alert, Redirectable};
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class ExpireComponent extends Component
{
    use Alert;
    use Redirectable;

    public Job $job;

    public bool $home = false;

    public ?string $reason = null;

    protected $validationAttributes = [
        'reason' => 'Razão',
    ];

    public function render(): View
    {
        return view('livewire.job.expire-job-component');
    }

    public function rules(): array
    {
        return [
            'reason' => [
                'required',
                'string',
                'in:' . implode(',', [
                    'A vaga foi preenchida',
                    'A vaga não está mais disponível',
                    'O processo seletivo foi concluído',
                    'O anúncio não é mais necessário',
                ]),
            ],
        ];
    }

    public function expire(): bool
    {
        $this->validate();

        if ($this->job->isInactive()) {
            $this->reset('reason');

            return $this->error(__("Esse anúncio não pode ser expirado.\nEle não esta ativo ou em revisão"));
        }

        try {
            DB::transaction(function () {
                $this->job->status     = JobStatus::Expired;
                $this->job->link       = null;
                $this->job->deleted_at = now();

                $this->job->save();

                $this->job
                    ->candidacy()
                    ->delete();

                $this->job
                    ->result()
                    ->create([
                        'type'        => JobResult::Expired,
                        'description' => $this->reason,
                    ]);
            });

            match ($this->home) {
                true  => $this->emitTo(OverviewComponent::class, 'overview::job::refresh'),
                false => $this->redirecting(route('index')),
            };

            $this->reset('reason');
            $this->dispatchBrowserEvent('close');

            return $this->success();
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error();
    }
}
