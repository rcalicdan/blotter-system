<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\CriminalRecord;
use App\Models\Person;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Person Profile')]
class ViewPage extends Component
{
    public Person $person;

    public function mount(Person $person): void
    {
        $this->authorize('view', $person);

        $this->person = $person->load([
            'criminalRecords',
            'blotterParties.blotterEntry',
            'disputeParties.dispute'
        ]);
    }

    public function toggleCriminalStatus(): void
    {
        $this->authorize('update', $this->person);
        $this->person->update(['is_criminal' => ! $this->person->is_criminal]);
        $this->dispatch('notify', message: "Status updated.", type: 'success');
    }

    public function deleteRecord(int $id): void
    {
        $record = CriminalRecord::findOrFail($id);
        $this->authorize('delete', $record);

        $record->delete();

        if ($this->person->criminalRecords()->count() === 0) {
            $this->person->update(['is_criminal' => false]);
        }

        $this->person->load('criminalRecords');
        $this->dispatch('notify', message: "Criminal record deleted.", type: 'success');
    }

    public function render()
    {
        return view('livewire.people.view-page');
    }
}