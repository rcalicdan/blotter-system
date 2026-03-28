<?php

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('User Management')]
class TablePage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    protected $queryString = [
        'search'     => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'sortField'  => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function getSortIcon(string $field): string
    {
        if ($this->sortField !== $field) {
            return 'sort';
        }

        return $this->sortDirection === 'asc' ? 'sort-asc' : 'sort-desc';
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
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, fn ($query) => $query->where('role', $this->roleFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users.table-page', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }
}