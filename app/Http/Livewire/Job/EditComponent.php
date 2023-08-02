<?php

namespace App\Http\Livewire\Job;

use App\Enums\{JobRequirements, JobStatus};
use App\Http\Livewire\Traits\{Alert, Redirectable, Searchable};
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class EditComponent extends Component
{
    use Alert;
    use Searchable;
    use Redirectable;
    use AuthorizesRequests;

    public Job $job;

    protected $listeners = [
        'update',
        'cancel',
    ];

    protected array $validationAttributes = [
        'job.title'                => 'Título',
        'job.description'          => 'Descrição',
        'job.company.name'         => 'Empresa',
        'job.company.site'         => 'Site da Empresa',
        'job.model'                => 'Modelo',
        'job.type'                 => 'Tipo',
        'job.specification'        => 'Especificação',
        'job.modality'             => 'Modalidade',
        'job.remuneration.type'    => 'Remuneração',
        'job.remuneration.value'   => 'Valor',
        'job.configuration.resume' => 'Configuração',
        'job.configuration.github' => 'Configuração',
        'job.notification'         => 'Notificação',
    ];

    public function mount(): void
    {
        $this->authorize('update', $this->job);

        $this->selected = $this->parse($this->job->requirement)->toArray();
    }

    protected function searching(): Collection
    {
        return collect(JobRequirements::toArray())
            ->map(fn ($value, $key) => ['id' => $key, 'name' => $value])
            ->filter(fn ($item) => str($item['name'])->contains($this->searching, true))
            ->filter(fn ($item, $key) => collect(data_get($this->selected, '*.id'))->contains($key) === false);
    }

    public function render(): View
    {
        return view('livewire.job.edit-job-component');
    }

    public function rules(): array
    {
        $remuneration = data_get($this->job, 'remuneration.type');

        return [
            'job.title' => [
                'required',
                'string',
                'max:50',
            ],
            'job.description' => [
                'required',
                'string',
                'min:100',
                'max:3000',
            ],
            'job.company.name' => [
                'required',
                'string',
                'max:100',
            ],
            'job.company.site' => [
                'nullable',
                'string',
                'url',
                'max:255',
            ],
            'job.model' => [
                'required',
            ],
            'job.type' => [
                'required',
            ],
            'job.specification' => [
                'required',
            ],
            'job.modality' => [
                'required',
            ],
            'job.remuneration.type' => [
                'required',
            ],
            'job.remuneration.value' => [
                Rule::when(filled($remuneration) && $remuneration !== 'interview', [
                    'required',
                    'integer',
                ]),
            ],
            'job.configuration.resume' => [
                'nullable',
            ],
            'job.configuration.github' => [
                'nullable',
            ],
            'job.notification' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function confirmingBeforeUpdate(): void
    {
        $this->bag([
            'confirm'     => 'update',
            'cancel'      => 'cancel',
            'dismissable' => false,
        ])->confirm(__("A edição será submetida a uma análise.\nVocê deseja prosseguir?"), 'Analise de Atualização');
    }

    public function update(): bool
    {
        if (!$this->job->isActive()) {
            return $this->error(__("Este anúncio não está ativo."));
        }

        $this->validate();

        try {
            DB::transaction(function () {
                $this->job->requirement = $this->selecteds()->values()->toArray();
                $this->job->status      = JobStatus::Review;

                $this->job->save();
            });

            return $this->success(__("Os dados serão submetidos a uma analise.\nVocê receberá uma resposta em seu e-mail."), __('Formulário Enviado!'));
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error();
    }

    public function cancel(): void
    {
        $this->job->refresh();

        $this->success(__('Edição cancelada com sucesso.'));
    }
}
