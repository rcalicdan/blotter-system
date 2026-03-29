<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create User')]
class CreatePage extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';

    public function mount(): void
    {
        $this->authorize('create', User::class);
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:'.implode(',', array_column(UserRole::cases(), 'value'))],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        User::create($validated);

        RedirectNotification::success("User \"{$this->first_name} {$this->last_name}\" has been created.");

        $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        $roles = auth()->user()->isAdmin()
            ? collect(UserRole::cases())->filter(fn ($role) => $role === UserRole::Staff)
            : collect(UserRole::cases());

        return view('livewire.users.create-page', [
            'roles' => $roles,
        ]);
    }
}
