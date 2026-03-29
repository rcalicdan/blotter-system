<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class SearchableDropdown extends Component
{
    #[Modelable]
    public $value = null;

    public string $search = '';
    public string $displayValue = '';
    public bool $open = false;
    public array $results = [];
    public string $model = '';
    public array $searchFields = [];
    public string $displayField = 'full_name';
    public string $placeholder = 'Search...';
    public string $emptyMessage = 'No results found.';
    public int $limit = 10;

    public function mount(): void
    {
        if ($this->value) {
            $record = $this->model::find($this->value);
            $this->displayValue = $record?->{$this->displayField} ?? '';
        }
    }

    public function updatedSearch(): void
    {
        $this->open = true;
        $this->searchRecords();
    }

    public function searchRecords(): void
    {
        if (blank($this->search)) {
            $this->results = [];
            return;
        }

        $query = ($this->model)::query();

        foreach ($this->searchFields as $index => $field) {
            $method = $index === 0 ? 'where' : 'orWhere';
            $query->{$method}($field, 'like', "%{$this->search}%");
        }

        $this->results = $query
            ->limit($this->limit)
            ->get()
            ->map(fn ($item) => [
                'id'    => $item->id,
                'label' => $item->{$this->displayField},
            ])
            ->toArray();
    }

    public function select(int $id, string $label): void
    {
        $this->value        = $id;
        $this->displayValue = $label;
        $this->search       = '';
        $this->open         = false;
        $this->results      = [];
    }

    public function clear(): void
    {
        $this->value        = null;
        $this->displayValue = '';
        $this->search       = '';
        $this->open         = false;
        $this->results      = [];
    }

    public function render()
    {
        return view('components.form.searchable-dropdown');
    }
}