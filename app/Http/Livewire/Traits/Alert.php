<?php

namespace App\Http\Livewire\Traits;

use Exception;
use Illuminate\Support\Str;
use Throwable;

trait Alert
{
    protected ?string $event = null;

    protected ?string $type = null;

    protected ?string $title = null;

    protected ?string $text = null;

    protected array $bag = [];

    protected function bag(array $alertbag): self
    {
        $this->bag = $alertbag;

        return $this;
    }

    /** @throws Throwable */
    protected function alert(
        string $title,
        string $message,
        string $type = 'success',
        ?string $event = 'swal:common'
    ): bool {
        $this->event($event)
             ->type($type)
             ->title($title)
             ->text($message);

        return $this->dispatching();
    }

    protected function text(string $alertText): self
    {
        $this->text = $alertText;

        return $this;
    }

    protected function title(string $alertTitle): self
    {
        $this->title = $alertTitle;

        return $this;
    }

    protected function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    protected function event(string $alertEvent): self
    {
        $this->event = $alertEvent;

        return $this;
    }

    /** @throws Throwable */
    protected function confirm(?string $message = 'Você tem certeza que deseja prosseguir?', ?string $title = null): bool
    {
        $this->event('swal:confirm')
            ->type('warning')
            ->title($title ?? __('Atenção!'))
            ->text($message);

        return $this->dispatching();
    }

    /** @throws Throwable */
    protected function success(?string $message = 'Procedimento realizado com sucesso', ?string $title = null): bool
    {
        $this->event('swal:common')
            ->type('success')
            ->title($title ?? __('Sucesso!'))
            ->text($message);

        return $this->dispatching();
    }

    /** @throws Throwable */
    protected function error(?string $message = "Ocorreu um erro interno.\nTente novamente mais tarde.", ?string $title = null): bool
    {
        $this->event('swal:common')
            ->type('error')
            ->title($title ?? __('Ops!'))
            ->text($message);

        return $this->dispatching();
    }

    /** @throws Throwable */
    private function dispatching(): bool
    {
        $this->validations();

        $data = [
            'type'    => $this->type,
            'message' => $this->title,
            'text'    => $this->text,
        ];

        $this->dispatchBrowserEvent($this->event, array_merge($data, $this->bag));

        return true;
    }

    /** @throws Throwable */
    private function validations(): bool
    {
        throw_if(
            !in_array($this->type, [
                'success',
                'error',
                'warning',
                'info',
            ]),
            new Exception(__(
                'The :method is invalid. Allowed is: success, error, warning and info',
                ['method' => $this->type]
            ))
        );

        throw_if(!Str::contains($this->event, 'swal:'), new Exception(__('There is no "swal:" syntax in the event name.')));

        return true;
    }
}
