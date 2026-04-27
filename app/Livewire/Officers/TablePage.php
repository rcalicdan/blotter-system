<?php

declare(strict_types=1);

namespace App\Livewire\Officers;

use App\Models\Officer;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Officers')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    protected array $sortableColumns =['first_name', 'last_name', 'badge_number', 'status'];

    public function mount(): void
    {
        $this->authorize('viewAny', Officer::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $officer = Officer::findOrFail($id);
        $this->authorize('delete', $officer);
        
        $officer->delete();
        $this->dispatch('notify', message: "Officer deleted.", type: 'success');
    }

    public function render()
    {
        $officers = Officer::query()
            ->when($this->search, fn ($query) => $query
                ->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhere('badge_number', 'like', "%{$this->search}%")
            )
            ->tap(fn ($query) => $this->applySorting($query))
            ->paginate(10);

        return view('livewire.officers.table-page', ['officers' => $officers]);
    }
}