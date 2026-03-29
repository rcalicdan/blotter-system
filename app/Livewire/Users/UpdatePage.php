<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Update User')]
class UpdatePage extends Component
{
    public User $user;

    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';

    public function mount(User $user): void
    {
        $this->authorize('update', $user);

        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->role = $user->role->value;
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:'.implode(',', array_column(UserRole::cases(), 'value'))],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->user->fill([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (filled($validated['password'])) {
            $this->user->password = $validated['password'];
        }

        $this->user->save();

        session()->flash('notify', [
            'message' => "User \"{$this->user->name}\" has been updated.",
            'type' => 'success',
        ]);

        $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.users.update-page', [
            'roles' => UserRole::cases(),
        ]);
    }
}
