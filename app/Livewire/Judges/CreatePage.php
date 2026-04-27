<?php

declare(strict_types=1);

namespace App\Livewire\Judges;

use App\Models\Judge;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Add Judge')]
class CreatePage extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $court_branch = '';
    public string $status = 'Active';

    public function mount(): void
    {
        $this->authorize('create', Judge::class);
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'court_branch' => ['nullable', 'string', 'max:255'],
            'status' =>['required', 'string', 'in:Active,Retired'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        Judge::create($validated);
        RedirectNotification::success("Judge \"{$this->first_name} {$this->last_name}\" has been added.");
        $this->redirect(route('judges.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.judges.create-page');
    }
}