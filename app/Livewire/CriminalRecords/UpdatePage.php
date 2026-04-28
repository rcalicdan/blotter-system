<?php

declare(strict_types=1);

namespace App\Livewire\CriminalRecords;

use App\Models\CriminalRecord;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Criminal Record')]
class UpdatePage extends Component
{
    public CriminalRecord $record;
    public string $charge = '';
    public string $date_committed = '';
    public string $status = '';
    public string $notes = '';

    public function mount(CriminalRecord $record): void
    {
        $this->authorize('update', $record);

        $this->record = $record->load('person');
        $this->charge = $record->charge;
        $this->date_committed = $record->date_committed->format('Y-m-d');
        $this->status = $record->status;
        $this->notes = $record->notes ?? '';
    }

    protected function rules(): array
    {
        return[
            'charge' => ['required', 'string', 'max:255'],
            'date_committed' => ['required', 'date'],
            'status' =>['required', 'string', 'in:Arrested,Wanted,Convicted,Cleared'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->record->update($this->validate());
        RedirectNotification::success("Record updated successfully.");
        $this->redirect(route('people.view', $this->record->person_id), navigate: true);
    }

    public function render()
    {
        return view('livewire.criminal-records.update-page');
    }
}