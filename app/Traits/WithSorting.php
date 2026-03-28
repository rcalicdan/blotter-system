<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

trait WithSorting
{
    #[Url(except: '')]
    public string $sortField = '';

    #[Url(except: 'asc')]
    public string $sortDirection = 'asc';

    public function sortBy(string $field): void
    {
        if (! $this->isSortable($field)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function isSortable(string $field): bool
    {
        return isset($this->sortableColumns) && \in_array($field, $this->sortableColumns);
    }

    public function getSortIcon(string $field): string
    {
        if ($this->sortField !== $field) {
            return 'sort';
        }

        return $this->sortDirection === 'asc' ? 'sort-asc' : 'sort-desc';
    }

    protected function applySorting(Builder $query): Builder
    {
        if ($this->sortField && $this->isSortable($this->sortField)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query;
    }
}
