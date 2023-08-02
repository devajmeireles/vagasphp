<?php

namespace App\Http\Livewire\User;

use App\Http\Livewire\Traits\Alert;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\{Component, WithFileUploads};
use Throwable;

class ResumeComponent extends Component
{
    use Alert;
    use WithFileUploads;

    public User $user;

    /** @var UploadedFile */
    public $resume;

    public array $rules = [
        'resume' => [
            'file',
            'mimes:pdf',
            'max:10240',
            'mimetypes:application/pdf',
        ],
    ];

    public function render(): View
    {
        return view('livewire.user.resume-component');
    }

    public function updatedResume(): bool
    {
        $this->validate();

        $name = sprintf('%s.pdf', Str::lower(Str::random(20)));

        try {
            if ($this->user->resume !== null) {
                Storage::delete(sprintf('public/resumes/%s', $this->user->resume));
            }

            $this->resume->storeAs('public/resumes', $name);

            $this->user
                ->update([
                    'resume' => $name,
                ]);

            $this->user->refresh();

            return $this->success(__("Currículo enviado com sucesso!"));
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error(__("Erro ao enviar currículo!\nTente novamente mais tarde!"));
    }

    public function remove(): bool
    {
        if (
            $this->user
                ->candidacy()
                ->count() > 0
        ) {
            return $this->error(__("Você não pode remover o seu currículo\nenquanto houver candidaturas em andamento."));
        }

        try {
            Storage::delete(sprintf('public/resumes/%s', $this->user->resume));

            $this->user
                ->update([
                    'resume' => null,
                ]);

            return $this->success(__("Currículo removido com sucesso!"));
        } catch (Throwable $e) {
            report($e);
        }

        return $this->error(__("Erro ao currículo arquivo!\nTente novamente mais tarde!"));
    }
}
