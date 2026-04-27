<?php

declare(strict_types=1);

namespace App\Livewire\Judges;

use App\Models\Judge;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Judge')]
class UpdatePage extends Component
{
    public Judge $judge;
    public string $first_name = '';
    public string $last_name = '';
    public string $court_branch = '';
    public string $status = '';

    public function mount(Judge $judge): void
    {
        $this->authorize('update', $judge);

        $this->judge = $judge;
        $this->first_name = $judge->first_name;
        $this->last_name = $judge->last_name;
        $this->court_branch = $judge->court_branch ?? '';
        $this->status = $judge->status;
    }

    protected function rules(): array
    {
        return [
            'first_name' =>['required', 'string', 'max:255'],
            'last_name' =>['required', 'string', 'max:255'],
            'court_branch' =>['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Active,Retired'],
        ];
    }

    public function save(): void
    {
        $this->judge->update($this->validate());
        RedirectNotification::success("Judge \"{$this->first_name} {$this->last_name}\" has been updated.");
        $this->redirect(route('judges.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.judges.update-page');
    }
}