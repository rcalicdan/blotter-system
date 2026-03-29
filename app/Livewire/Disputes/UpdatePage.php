<?php

declare(strict_types=1);

namespace App\Livewire\Disputes;

use App\Enums\DisputePartyRole;
use App\Enums\DisputeStatus;
use App\Models\Dispute;
use App\Models\DisputeParty;
use App\Services\RedirectNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Dispute')]
class UpdatePage extends Component
{
    public Dispute $dispute;

    public string $case_number = '';
    public string $subject = '';
    public string $description = '';
    public string $status = '';
    public ?int $blotter_id = null;
    public ?int $assigned_to = null;
    public array $parties = [];

    public function mount(Dispute $dispute): void
    {
        $this->authorize('update', $dispute);

        $this->dispute     = $dispute;
        $this->case_number = $dispute->case_number;
        $this->subject     = $dispute->subject;
        $this->description = $dispute->description ?? '';
        $this->status      = $dispute->status->value;
        $this->blotter_id  = $dispute->blotter_id;
        $this->assigned_to = $dispute->assigned_to;

        $this->parties = $dispute->parties->map(fn ($party) => [
            'person_id' => $party->person_id,
            'role'      => $party->role->value,
        ])->toArray();
    }

    protected function rules(): array
    {
        return [
            'case_number'         => ['required', 'string', 'max:255', Rule::unique('disputes', 'case_number')->ignore($this->dispute->id)],
            'subject'             => ['required', 'string', 'max:500'],
            'description'         => ['nullable', 'string'],
            'status'              => ['required', 'string', 'in:'.implode(',', array_column(DisputeStatus::cases(), 'value'))],
            'blotter_id'          => ['nullable', 'exists:blotter_entries,id'],
            'assigned_to'         => ['nullable', 'exists:users,id'],
            'parties'             => ['required', 'array', 'min:2'],
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
            $this->dispute->update([
                'case_number' => $validated['case_number'],
                'subject'     => $validated['subject'],
                'description' => $validated['description'],
                'status'      => $validated['status'],
                'blotter_id'  => $validated['blotter_id'],
                'assigned_to' => $validated['assigned_to'],
            ]);

            $this->dispute->parties()->delete();

            foreach ($validated['parties'] as $party) {
                DisputeParty::create([
                    'dispute_id' => $this->dispute->id,
                    'person_id'  => $party['person_id'],
                    'role'       => $party['role'],
                ]);
            }
        });

        RedirectNotification::success("Dispute \"{$this->dispute->case_number}\" has been updated.");

        $this->redirect(route('disputes.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.disputes.update-page', [
            'statuses'   => DisputeStatus::cases(),
            'partyRoles' => DisputePartyRole::cases(),
        ]);
    }
}