<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use App\Services\PhotoUploadService;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('People')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    protected array $sortableColumns = ['first_name', 'last_name', 'created_at'];

    public function mount(): void
    {
        $this->authorize('viewAny', Person::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id, PhotoUploadService $uploader): void
    {
        $person = Person::findOrFail($id);

        $this->authorize('delete', $person);

        $uploader->delete($person->photo);

        $person->delete();

        $this->dispatch('notify', message: "Person \"{$person->full_name}\" has been deleted.", type: 'success');
    }

    public function render()
    {
        $people = Person::query()
            ->when(
                $this->search,
                fn ($query) => $query
                    ->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('contact_number', 'like', "%{$this->search}%")
            )
            ->tap(fn ($query) => $this->applySorting($query))
            ->paginate(10)
        ;

        return view('livewire.people.table-page', [
            'people' => $people,
        ]);
    }
}
