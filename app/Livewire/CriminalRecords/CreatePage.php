<?php

declare(strict_types=1);

namespace App\Livewire\CriminalRecords;

use App\Models\CriminalRecord;
use App\Models\Person;
use App\Services\RedirectNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Add Criminal Record')]
class CreatePage extends Component
{
    public Person $person;
    public string $charge = '';
    public string $date_committed = '';
    public string $status = 'Arrested';
    public string $notes = '';

    public function mount(Person $person): void
    {
        $this->authorize('create', CriminalRecord::class);
        $this->person = $person;
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
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            CriminalRecord::create([
                'person_id' => $this->person->id,
                ...$validated
            ]);
            $this->person->update(['is_criminal' => true]);
        });

        RedirectNotification::success("Record added for {$this->person->full_name}.");
        $this->redirect(route('people.view', $this->person), navigate: true);
    }

    public function render()
    {
        return view('livewire.criminal-records.create-page');
    }
}