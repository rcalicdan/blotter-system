<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use App\Traits\WithSorting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('User Management')]
class TablePage extends Component
{
    use WithPagination;
    use WithSorting;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $roleFilter = '';

    protected array $sortableColumns = ['name', 'email', 'role', 'created_at'];

    public function mount(): void
    {
        $this->authorize('view', User::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('notify', message: 'You cannot delete your own account.', type: 'error');

            return;
        }

        $user->delete();

        $this->dispatch('notify', message: "User \"{$user->name}\" has been deleted.", type: 'success');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                    ;
                });
            })
            ->when($this->roleFilter, fn ($query) => $query->where('role', $this->roleFilter))
            ->tap(fn ($query) => $this->applySorting($query))
            ->paginate(10)
        ;

        return view('livewire.users.table-page', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }
}
