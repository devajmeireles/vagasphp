<?php

namespace App\Http\Livewire\Traits;

trait Redirectable
{
    public function redirecting(string $route, int $time = 3000): void
    {
        $this->dispatchBrowserEvent('redirect', [
            'route' => $route,
            'time'  => $time,
        ]);
    }
}
