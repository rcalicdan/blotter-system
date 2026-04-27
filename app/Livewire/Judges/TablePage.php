<?php

declare(strict_types=1);

namespace App\Livewire\Judges;

use App\Models\Judge;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Judges')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    protected array $sortableColumns =['first_name', 'last_name', 'court_branch', 'status'];

    public function mount(): void
    {
        $this->authorize('viewAny', Judge::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $judge = Judge::findOrFail($id);
        $this->authorize('delete', $judge);

        $judge->delete();
        $this->dispatch('notify', message: "Judge deleted.", type: 'success');
    }

    public function render()
    {
        $judges = Judge::query()
            ->when($this->search, fn ($query) => $query
                ->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhere('court_branch', 'like', "%{$this->search}%")
            )
            ->tap(fn ($query) => $this->applySorting($query))
            ->paginate(10);

        return view('livewire.judges.table-page', ['judges' => $judges]);
    }
}