<?php

declare(strict_types=1);

namespace App\Livewire\Disputes;

use App\Enums\DisputeStatus;
use App\Models\Dispute;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Disputes')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $statusFilter = '';

    protected array $sortableColumns = ['case_number', 'subject', 'status', 'created_at'];

    public function mount(): void
    {
        $this->authorize('viewAny', Dispute::class);
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
        $dispute = Dispute::findOrFail($id);

        $this->authorize('delete', $dispute);

        $dispute->delete();

        $this->dispatch('notify', message: "Dispute \"{$dispute->case_number}\" has been deleted.", type: 'success');
    }

    public function render()
    {
        $disputes = Dispute::query()
            ->with(['filer', 'officer', 'parties.person', 'blotterEntry'])
            ->when(
                $this->search,
                fn($query) => $query
                    ->where('case_number', 'like', "%{$this->search}%")
                    ->orWhere('subject', 'like', "%{$this->search}%")
            )
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->tap(fn($query) => $this->applySorting($query))
            ->paginate(10);

        return view('livewire.disputes.table-page', [
            'disputes' => $disputes,
            'statuses' => DisputeStatus::cases(),
        ]);
    }
}
