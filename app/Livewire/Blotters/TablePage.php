<?php

declare(strict_types=1);

namespace App\Livewire\Blotters;

use App\Enums\BlotterStatus;
use App\Models\BlotterEntry;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Blotter Entries')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $statusFilter = '';

    protected array $sortableColumns = ['blotter_number', 'incident_date', 'status', 'created_at'];

    public function mount(): void
    {
        $this->authorize('viewAny', BlotterEntry::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $entry = BlotterEntry::findOrFail($id);

        $this->authorize('delete', $entry);

        $entry->delete();

        $this->dispatch('notify', message: "Blotter entry \"{$entry->blotter_number}\" has been deleted.", type: 'success');
    }

    public function render()
    {
        $entries = BlotterEntry::query()
            ->with(['recorder', 'parties.person'])
            ->when($this->search, fn ($query) => $query
                ->where('blotter_number', 'like', "%{$this->search}%")
                ->orWhere('incident_location', 'like', "%{$this->search}%")
                ->orWhere('narrative', 'like', "%{$this->search}%")
            )
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->tap(fn ($query) => $this->applySorting($query))
            ->paginate(10);

        return view('livewire.blotters.table-page', [
            'entries'  => $entries,
            'statuses' => BlotterStatus::cases(),
        ]);
    }
}