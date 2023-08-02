<?php

namespace App\Http\Livewire\Traits;

use App\Enums\JobRequirements;
use Exception;
use Illuminate\Support\Collection;
use Throwable;

trait Searchable
{
    public ?string $searching = null;

    public mixed $results = null;

    public array $selected = [];

    /** @throws Throwable */
    public function updatedSearching(): void
    {
        throw_if(!method_exists($this, 'searching'), new Exception('Method searching not implemented to use Searchable trait.'));

        $this->reset('results');

        if (filled($this->searching)) {
            $this->results = $this->searching();
        }
    }

    public function select(mixed $selected): void
    {
        $this->selected[] = $selected;

        $this->selected = collect($this->selected)->unique()->toArray();

        $this->reset('results', 'searching');
    }

    public function unselect(mixed $selected): void
    {
        $this->selected = array_filter($this->selected, fn ($item) => $item['id'] !== $selected);
    }

    public function selecteds(): Collection
    {
        return collect($this->selected)->map(fn ($item) => $item['name']);
    }

    public function parse(Collection $requirements): Collection
    {
        return collect(JobRequirements::toArray())
            ->map(fn ($value, $key) => ['id' => $key, 'name' => $value])
            ->filter(fn ($item) => $requirements->contains($item['name']));
    }
}
