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

#[Title('Create Blotter Entry')]
class CreatePage extends Component
{
    public string $blotter_number = '';
    public string $incident_date = '';
    public string $incident_time = '';
    public string $incident_location = '';
    public string $narrative = '';
    public string $status = '';
    public array $parties = [];

    public function mount(): void
    {
        $this->authorize('create', BlotterEntry::class);

        $this->status         = BlotterStatus::Open->value;
        $this->incident_date  = now()->format('Y-m-d');
        $this->blotter_number = $this->generateBlotterNumber();

        $this->parties = [
            ['person_id' => null, 'role' => BlotterPartyRole::Complainant->value],
            ['person_id' => null, 'role' => BlotterPartyRole::Respondent->value],
        ];
    }

    private function generateBlotterNumber(): string
    {
        $year  = now()->year;
        $count = BlotterEntry::whereYear('created_at', $year)->count() + 1;

        return \sprintf('BLT-%d-%04d', $year, $count);
    }

    protected function rules(): array
    {
        return [
            'blotter_number'    => ['required', 'string', 'max:255', 'unique:blotter_entries,blotter_number'],
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
            $entry = BlotterEntry::create([
                'blotter_number'    => $validated['blotter_number'],
                'incident_date'     => $validated['incident_date'],
                'incident_time'     => $validated['incident_time'],
                'incident_location' => $validated['incident_location'],
                'narrative'         => $validated['narrative'],
                'status'            => $validated['status'],
                'recorded_by'       => auth()->id(),
            ]);

            foreach ($validated['parties'] as $party) {
                BlotterParty::create([
                    'blotter_id' => $entry->id,
                    'person_id'  => $party['person_id'],
                    'role'       => $party['role'],
                ]);
            }
        });

        RedirectNotification::success("Blotter entry \"{$this->blotter_number}\" has been created.");

        $this->redirect(route('blotters.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.blotters.create-page', [
            'statuses'   => BlotterStatus::cases(),
            'partyRoles' => BlotterPartyRole::cases(),
        ]);
    }
}