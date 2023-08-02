<?php

namespace App\Http\Livewire\Job;

use App\Enums\{JobContent, JobRequirements, JobStatus};
use App\Http\Livewire\Traits\{Alert, Redirectable, Searchable};
use App\Jobs\CreateJob;
use Illuminate\Contracts\View\View;
use Illuminate\Support\{Collection, Facades\Auth, Str};
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateComponent extends Component
{
    use Alert;
    use Searchable;
    use Redirectable;

    public ?string $format = null;

    public array $job = [];

    protected array $validationAttributes = [
        'job.title'                => 'Título',
        'job.description'          => 'Descrição',
        'job.company.name'         => 'Empresa',
        'job.company.site'         => 'Site da Empresa',
        'job.model'                => 'Modelo',
        'job.type'                 => 'Tipo',
        'job.specification'        => 'Especificação',
        'job.link'                 => 'URL de Redirecionamento',
        'job.modality'             => 'Modalidade',
        'job.remuneration.type'    => 'Remuneração',
        'job.remuneration.value'   => 'Valor',
        'job.configuration.resume' => 'Configuração',
        'job.configuration.github' => 'Configuração',
        'job.notification'         => 'Notificação',
    ];

    public function mount(): void
    {
        data_set($this->job, 'remuneration.type', null);
        data_set($this->job, 'notification', null);
    }

    public function render(): View
    {
        return view('livewire.job.create-job-component');
    }

    public function restart(): void
    {
        $this->resetExcept('format');
        $this->resetValidation();
    }

    public function rules(): array
    {
        $default = [
            'job.title' => [
                'required',
                'string',
                'max:50',
            ],
            'job.specification' => [
                'required',
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
        ];

        $redirectable = [
            'job.link' => [
                'required',
                'url',
                'max:255',
            ],
        ];

        $remuneration = data_get($this->job, 'remuneration.type');

        $detailable = [
            'job.description' => [
                'required',
                'string',
                'min:100',
                'max:3000',
            ],
            'job.model' => [
                'required',
            ],
            'job.type' => [
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
            'job.notification' => [
                'required',
                'string',
                'max:255',
            ],
        ];

        return array_merge($default, $this->format === 'redirectable' ? $redirectable : $detailable);
    }

    public function create(): void
    {
        $this->validate();

        if ($this->format === JobContent::Detailable->value) {
            data_set($this->job, 'link', Str::random(10));
        }

        CreateJob::dispatch(array_merge($this->job, [
            'user_id'     => Auth::id(),
            'status'      => JobStatus::Review,
            'content'     => JobContent::resolve($this->format),
            'requirement' => $this->selecteds()->toArray(),
        ]));

        $this->success(__("Os dados serão submetidos a uma analise.\nVocê receberá uma resposta em seu e-mail."), __('Formulário Enviado!'));

        $this->reset();

        $this->redirecting(route('index'), 5000);
    }

    protected function searching(): Collection
    {
        return collect(JobRequirements::toArray())
            ->map(fn ($value, $key) => ['id' => $key, 'name' => $value])
            ->filter(fn ($item) => str($item['name'])->contains($this->searching, true))
            ->filter(fn ($item, $key) => collect(data_get($this->selected, '*.id'))->contains($key) === false);
    }
}
