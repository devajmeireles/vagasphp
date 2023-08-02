<?php

namespace App\Http\Livewire\Job;

use App\Http\Livewire\Traits\Alert;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class ManipulateComponent extends Component
{
    use Alert;

    public Job $job;

    public bool $home = false;

    public function render(): View
    {
        return view('livewire.job.manipulate-job-component');
    }

    public function destroy(): bool
    {
        if ($this->job->isActive()) {
            return $this->error(__("Realize o encerramento do anúncio."));
        }

        //it was created because I think laravel can't understand a case
        // where we want to force delete a model without being soft deleted before
        $this->job->delete();

        try {
            DB::transaction(fn () => $this->job->onlyTrashed()->forceDelete());

            $this->emitTo(OverviewComponent::class, 'overview::job::refresh');

            return $this->success();
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error(__("Não foi possível excluir o anúncio"));
    }
}
