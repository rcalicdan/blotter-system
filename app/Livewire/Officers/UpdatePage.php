<?php

declare(strict_types=1);

namespace App\Livewire\Officers;

use App\Models\Officer;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Officer')]
class UpdatePage extends Component
{
    public Officer $officer;
    public string $first_name = '';
    public string $last_name = '';
    public string $badge_number = '';
    public string $rank = '';
    public string $status = '';

    public function mount(Officer $officer): void
    {
        $this->authorize('update', $officer);

        $this->officer = $officer;
        $this->first_name = $officer->first_name;
        $this->last_name = $officer->last_name;
        $this->badge_number = $officer->badge_number ?? '';
        $this->rank = $officer->rank ?? '';
        $this->status = $officer->status;
    }

    protected function rules(): array
    {
        return [
            'first_name' =>['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'badge_number' => ['nullable', 'string', 'max:255'],
            'rank' => ['nullable', 'string', 'max:255'],
            'status' =>['required', 'string', 'in:Active,Inactive'],
        ];
    }

    public function save(): void
    {
        $this->officer->update($this->validate());
        RedirectNotification::success("Officer \"{$this->first_name} {$this->last_name}\" has been updated.");
        $this->redirect(route('officers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.officers.update-page');
    }
}