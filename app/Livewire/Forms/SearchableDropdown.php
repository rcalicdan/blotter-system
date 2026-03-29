<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class SearchableDropdown extends Component
{
    #[Modelable]
    public $value = null;

    public string $search = '';
    public string $displayValue = '';
    public string $subDisplayValue = '';
    public bool $open = false;
    public array $results = [];
    public string $model = '';
    public array $searchFields = [];
    public string $displayField = 'full_name';
    public array $subLabelFields = [];
    public array $subLabelFieldFormats = [];
    public string $subLabelSeparator = ' · ';
    public string $placeholder = 'Search...';
    public string $emptyMessage = 'No results found.';
    public int $limit = 10;

    public function mount(): void
    {
        if ($this->value) {
            $record = $this->model::with($this->resolveEagerLoads())
                ->find($this->value);

            $this->displayValue    = data_get($record, $this->displayField) ?? '';
            $this->subDisplayValue = $this->resolveSubLabel($record);
        }
    }

    /**
     * Automatically derive relationships to eager load from
     * any dot-notation entries in $subLabelFields.
     * e.g. 'barangay.name'           → eager loads 'barangay'
     *      'household.address.street' → eager loads 'household.address'
     */
    private function resolveEagerLoads(): array
    {
        return collect($this->subLabelFields)
            ->filter(fn ($field) => str_contains($field, '.'))
            ->map(function ($field) {
                $parts = explode('.', $field);
                array_pop($parts);
                return implode('.', $parts);
            })
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Resolve a sub-label string from the record.
     * Supports dot notation for relationships e.g. 'barangay.name'.
     */
    private function resolveSubLabel(mixed $record): string
    {
        if (! $record || empty($this->subLabelFields)) {
            return '';
        }

        return collect($this->subLabelFields)
            ->map(function ($field) use ($record) {
                $value = data_get($record, $field);

                if (! $value) {
                    return null;
                }

                if (isset($this->subLabelFieldFormats[$field])) {
                    return Carbon::parse($value)
                        ->format($this->subLabelFieldFormats[$field]);
                }

                return $value;
            })
            ->filter()
            ->implode($this->subLabelSeparator);
    }

    public function updatedSearch(): void
    {
        $this->searchRecords();
    }

    public function searchRecords(): void
    {
        if (blank($this->search)) {
            $this->results = [];
            return;
        }

        $query = ($this->model)::query()
            ->with($this->resolveEagerLoads());

        $directFields = collect($this->searchFields)
            ->reject(fn ($field) => str_contains($field, '.'));
            
        $relationFields = collect($this->searchFields)
            ->filter(fn ($field) => str_contains($field, '.'))
            ->groupBy(fn ($field) => explode('.', $field)[0])
            ->map(fn ($fields) => $fields->map(function ($field) {
                $parts = explode('.', $field);
                array_shift($parts);
                return implode('.', $parts);
            }));

        $firstClause = true;

        foreach ($directFields as $field) {
            $method = $firstClause ? 'where' : 'orWhere';
            $query->{$method}($field, 'like', "%{$this->search}%");
            $firstClause = false;
        }

        foreach ($relationFields as $relation => $fields) {
            $method = $firstClause ? 'whereHas' : 'orWhereHas';
            $query->{$method}($relation, function ($q) use ($fields) {
                $innerFirst = true;
                foreach ($fields as $field) {
                    $innerMethod = $innerFirst ? 'where' : 'orWhere';
                    $q->{$innerMethod}($field, 'like', "%{$this->search}%");
                    $innerFirst = false;
                }
            });
            $firstClause = false;
        }

        $this->results = $query
            ->limit($this->limit)
            ->get()
            ->map(fn ($item) => [
                'id'        => $item->id,
                'label'     => data_get($item, $this->displayField),
                'sub_label' => $this->resolveSubLabel($item),
            ])
            ->toArray();
    }

    public function select(int $id, string $label, string $subLabel = ''): void
    {
        $this->value           = $id;
        $this->displayValue    = $label;
        $this->subDisplayValue = $subLabel;
        $this->search          = '';
        $this->open            = false;
        $this->results         = [];
    }

    public function clear(): void
    {
        $this->value           = null;
        $this->displayValue    = '';
        $this->subDisplayValue = '';
        $this->search          = '';
        $this->open            = false;
        $this->results         = [];
    }

    public function render()
    {
        return view('components.form.searchable-dropdown');
    }
}