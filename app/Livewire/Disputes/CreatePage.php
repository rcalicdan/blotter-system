<?php

declare(strict_types=1);

namespace App\Livewire\Disputes;

use App\Enums\DisputePartyRole;
use App\Enums\DisputeStatus;
use App\Models\Dispute;
use App\Models\DisputeParty;
use App\Services\RedirectNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Dispute')]
class CreatePage extends Component
{
    public string $case_number = '';
    public string $subject = '';
    public string $description = '';
    public string $status = '';
    public ?int $blotter_id = null;
    public ?int $assigned_to = null;
    public array $parties = [];

    public function mount(): void
    {
        $this->authorize('create', Dispute::class);

        $this->status      = DisputeStatus::Filed->value;
        $this->case_number = $this->generateCaseNumber();

        $this->parties = [
            ['person_id' => null, 'role' => DisputePartyRole::Complainant->value],
            ['person_id' => null, 'role' => DisputePartyRole::Respondent->value],
        ];
    }

    private function generateCaseNumber(): string
    {
        $year  = now()->year;
        $count = Dispute::whereYear('created_at', $year)->count() + 1;

        return sprintf('DSP-%d-%04d', $year, $count);
    }

    protected function rules(): array
    {
        return [
            'case_number'       => ['required', 'string', 'max:255', 'unique:disputes,case_number'],
            'subject'           => ['required', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'status'            => ['required', 'string', 'in:'.implode(',', array_column(DisputeStatus::cases(), 'value'))],
            'blotter_id'        => ['nullable', 'exists:blotter_entries,id'],
            'assigned_to'       => ['nullable', 'exists:users,id'],
            'parties'           => ['required', 'array', 'min:2'],
            'parties.*.person_id' => ['required', 'exists:people,id'],
            'parties.*.role'      => ['required', 'string', 'in:'.implode(',', array_column(DisputePartyRole::cases(), 'value'))],
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
        $this->parties[] = ['person_id' => null, 'role' => DisputePartyRole::Complainant->value];
    }

    public function removeParty(int $index): void
    {
        if (count($this->parties) <= 2) {
            return;
        }

        array_splice($this->parties, $index, 1);
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $dispute = Dispute::create([
                'case_number' => $validated['case_number'],
                'subject'     => $validated['subject'],
                'description' => $validated['description'],
                'status'      => $validated['status'],
                'blotter_id'  => $validated['blotter_id'],
                'assigned_to' => $validated['assigned_to'],
                'filed_by'    => auth()->id(),
            ]);

            foreach ($validated['parties'] as $party) {
                DisputeParty::create([
                    'dispute_id' => $dispute->id,
                    'person_id'  => $party['person_id'],
                    'role'       => $party['role'],
                ]);
            }
        });

        RedirectNotification::success("Dispute \"{$this->case_number}\" has been created.");

        $this->redirect(route('disputes.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.disputes.create-page', [
            'statuses'   => DisputeStatus::cases(),
            'partyRoles' => DisputePartyRole::cases(),
        ]);
    }
}