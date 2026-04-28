<?php

declare(strict_types=1);

namespace App\Livewire\Criminals;

use App\Models\Person;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Criminals & Offenders')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    protected array $sortableColumns =['first_name', 'last_name', 'created_at'];

    public function mount(): void
    {
        $this->authorize('viewAny', Person::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $criminals = Person::query()
            ->where('is_criminal', true)
            ->withCount('criminalRecords')
            ->when($this->search, fn ($query) => $query
                ->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
            )
            ->tap(fn ($query) => $this->applySorting($query))
            ->paginate(10);

        return view('livewire.criminals.table-page', ['criminals' => $criminals]);
    }
}