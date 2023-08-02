<?php

namespace App\Http\Livewire\Job;

use App\Enums\{JobModality, JobModel, JobStatus};
use App\Http\Livewire\Traits\Alert;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OverviewComponent extends Component
{
    use Alert;

    public int $quantity = 10;

    public array $filters = [];

    public bool $remote = false;

    public bool $contract = false;

    public bool $authenticated = false;

    protected $listeners = [
        'overview::job::refresh' => '$refresh',
    ];

    public function render(): View
    {
        $jobs = $this->data();

        return view('livewire.job.overview-job-component', compact('jobs'));
    }

    private function data(): Collection
    {
        return Job::with('user')
            ->when($this->authenticated, fn (Builder $query) => $query->where('user_id', '=', Auth::id())->withTrashed())
            ->when($this->remote, fn (Builder $query) => $query->where('modality', '=', JobModality::Remote))
            ->when($this->contract, fn (Builder $query) => $query->where('model', '=', JobModel::Contract))
            ->when(!empty($this->filters), fn (Builder $query) => $query->whereIn('specification', $this->filters))
            ->when(
                !$this->authenticated,
                fn (Builder $query) => $query->where('status', '=', JobStatus::Actived)
                    ->latest('priority')
                    ->latest('created_at')
            )
            ->take($this->quantity)
            ->get();
    }

    public function load(): void
    {
        $this->quantity += 10;
    }

    public function authenticated(): void
    {
        $this->authenticated = !$this->authenticated;

        $this->emitSelf('overview::job::refresh');
    }

    public function filter(int $specification): void
    {
        if (isset($this->filters[$specification])) {
            unset($this->filters[$specification]);
        } else {
            $this->filters[$specification] = $specification;
        }

        $this->filters = array_unique($this->filters);

        $this->emitSelf('overview::job::refresh');
    }

    public function clear(): void
    {
        $this->reset('filters', 'authenticated');
    }
}
