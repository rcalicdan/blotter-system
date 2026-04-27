<?php

declare(strict_types=1);

namespace App\Livewire\Officers;

use App\Models\Officer;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Add Officer')]
class CreatePage extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $badge_number = '';
    public string $rank = '';
    public string $status = 'Active';

    public function mount(): void
    {
        $this->authorize('create', Officer::class);
    }

    protected function rules(): array
    {
        return[
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'badge_number' => ['nullable', 'string', 'max:255'],
            'rank' =>['nullable', 'string', 'max:255'],
            'status' =>['required', 'string', 'in:Active,Inactive'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        Officer::create($validated);
        RedirectNotification::success("Officer \"{$this->first_name} {$this->last_name}\" has been added.");
        $this->redirect(route('officers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.officers.create-page');
    }
}