<?php

declare(strict_types=1);

namespace App\Livewire\Blotters;

use App\Enums\BlotterPartyRole;
use App\Enums\BlotterStatus;
use App\Models\BlotterEntry;
use App\Models\BlotterParty;
use App\Services\RedirectNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Blotter Entry')]
class UpdatePage extends Component
{
    public BlotterEntry $blotterEntry;
    public string $blotter_number = '';
    public string $incident_date = '';
    public string $incident_time = '';
    public string $incident_location = '';
    public string $narrative = '';
    public string $status = '';
    public array $parties = [];

    public function mount(BlotterEntry $blotterEntry): void
    {
        $this->authorize('update', $blotterEntry);

        $this->blotterEntry     = $blotterEntry;
        $this->blotter_number   = $blotterEntry->blotter_number;
        $this->incident_date    = $blotterEntry->incident_date->format('Y-m-d');
        $this->incident_time    = $blotterEntry->incident_time ?? '';
        $this->incident_location = $blotterEntry->incident_location;
        $this->narrative        = $blotterEntry->narrative;
        $this->status           = $blotterEntry->status->value;

        $this->parties = $blotterEntry->parties->map(fn ($party) => [
            'person_id' => $party->person_id,
            'role'      => $party->role->value,
        ])->toArray();
    }

    protected function rules(): array
    {
        return [
            'blotter_number'    => ['required', 'string', 'max:255', "unique:blotter_entries,blotter_number,{$this->blotterEntry->id}"],
            'incident_date'     => ['required', 'date'],
            'incident_time'     => ['nullable', 'date_format:H:i'],
            'incident_location' => ['required', 'string', 'max:500'],
            'narrative'         => ['required', 'string'],
            'status'            => ['required', 'string', 'in:'.implode(',', array_column(BlotterStatus::cases(), 'value'))],
            'parties'           => ['required', 'array', 'min:1'],
            'parties.*.person_id' => ['required', 'exists:people,id'],
            'parties.*.role'      => ['required', 'string', 'in:'.implode(',', array_column(BlotterPartyRole::cases(), 'value'))],
        ];
    }

    protected function messages(): array
    {
        return [
            'parties.*.person_id.required' => 'Please select a person for each party.',
            'parties.*.person_id.exists'   => 'The selected person does not exist.',
            'parties.*.role.required'      => 'Please select a role for each party.',
        ];
    }

    public function addParty(): void
    {
        $this->parties[] = ['person_id' => null, 'role' => BlotterPartyRole::Witness->value];
    }

    public function removeParty(int $index): void
    {
        if (\count($this->parties) <= 2) {
            return;
        }

        array_splice($this->parties, $index, 1);
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $this->blotterEntry->update([
                'blotter_number'    => $validated['blotter_number'],
                'incident_date'     => $validated['incident_date'],
                'incident_time'     => $validated['incident_time'],
                'incident_location' => $validated['incident_location'],
                'narrative'         => $validated['narrative'],
                'status'            => $validated['status'],
            ]);

            $this->blotterEntry->parties()->delete();

            foreach ($validated['parties'] as $party) {
                BlotterParty::create([
                    'blotter_id' => $this->blotterEntry->id,
                    'person_id'  => $party['person_id'],
                    'role'       => $party['role'],
                ]);
            }
        });

        RedirectNotification::success("Blotter entry \"{$this->blotter_number}\" has been updated.");

        $this->redirect(route('blotters.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.blotters.update-page', [
            'statuses'   => BlotterStatus::cases(),
            'partyRoles' => BlotterPartyRole::cases(),
        ]);
    }
}