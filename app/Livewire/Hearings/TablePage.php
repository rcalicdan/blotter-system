<?php

declare(strict_types=1);

namespace App\Livewire\Hearings;

use App\Enums\HearingStatus;
use App\Models\Hearing;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Hearings')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $statusFilter = '';

    protected array $sortableColumns = ['scheduled_date', 'location', 'status', 'created_at'];

    public function mount(): void
    {
        $this->authorize('viewAny', Hearing::class);
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
        $hearing = Hearing::findOrFail($id);

        $this->authorize('delete', $hearing);

        $hearing->delete();

        $this->dispatch('notify', message: "Hearing has been deleted.", type: 'success');
    }

    public function render()
    {
        $hearings = Hearing::query()
            ->with(['dispute', 'judge', 'attendees.person'])
            ->when(
                $this->search,
                fn($query) => $query
                    ->where('location', 'like', "%{$this->search}%")
                    ->orWhereHas('dispute', fn($q) => $q->where('case_number', 'like', "%{$this->search}%"))
            )
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->tap(fn($query) => $this->applySorting($query))
            ->paginate(10);

        return view('livewire.hearings.table-page', [
            'hearings' => $hearings,
            'statuses' => HearingStatus::cases(),
        ]);
    }
}
