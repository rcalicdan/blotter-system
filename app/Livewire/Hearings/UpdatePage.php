<?php

declare(strict_types=1);

namespace App\Livewire\Hearings;

use App\Enums\HearingStatus;
use App\Models\Dispute;
use App\Models\Hearing;
use App\Models\HearingAttendee;
use App\Services\RedirectNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Hearing')]
class UpdatePage extends Component
{
    public Hearing $hearing;

    public ?int $dispute_id = null;
    public string $scheduled_date = '';
    public string $scheduled_time = '';
    public string $location = '';
    public string $status = '';
    public string $notes = '';
    public ?int $conducted_by = null;
    public array $attendees = [];

    public function mount(Hearing $hearing): void
    {
        $this->authorize('update', $hearing);

        $this->hearing        = $hearing->load(['attendees.person', 'dispute.parties.person']);
        $this->dispute_id     = $hearing->dispute_id;
        $this->scheduled_date = $hearing->scheduled_date->format('Y-m-d');
        $this->scheduled_time = $hearing->scheduled_time ?? '';
        $this->location       = $hearing->location;
        $this->status         = $hearing->status->value;
        $this->notes          = $hearing->notes ?? '';
        $this->conducted_by   = $hearing->conducted_by;

        // Load existing attendees
        $this->attendees = $hearing->attendees->map(fn ($attendee) => [
            'person_id' => $attendee->person_id,
            'name'      => $attendee->person->full_name,
            'role'      => $this->resolvePartyRole($attendee->person_id),
            'attended'  => $attendee->attended,
        ])->toArray();
    }

    private function resolvePartyRole(int $personId): string
    {
        $party = $this->hearing->dispute?->parties
            ->firstWhere('person_id', $personId);

        return $party?->role->value ?? '—';
    }

    protected function rules(): array
    {
        return [
            'dispute_id'            => ['required', 'exists:disputes,id'],
            'scheduled_date'        => ['required', 'date'],
            'scheduled_time'        => ['nullable', 'date_format:H:i'],
            'location'              => ['required', 'string', 'max:500'],
            'status'                => ['required', 'string', 'in:'.implode(',', array_column(HearingStatus::cases(), 'value'))],
            'notes'                 => ['nullable', 'string'],
            'conducted_by'          => ['nullable', 'exists:users,id'],
            'attendees'             => ['nullable', 'array'],
            'attendees.*.person_id' => ['required', 'exists:people,id'],
            'attendees.*.attended'  => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $this->hearing->update([
                'dispute_id'     => $validated['dispute_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'],
                'location'       => $validated['location'],
                'status'         => $validated['status'],
                'notes'          => $validated['notes'],
                'conducted_by'   => $validated['conducted_by'],
            ]);

            $this->hearing->attendees()->delete();

            foreach ($validated['attendees'] ?? [] as $attendee) {
                HearingAttendee::create([
                    'hearing_id' => $this->hearing->id,
                    'person_id'  => $attendee['person_id'],
                    'attended'   => $attendee['attended'] ?? false,
                ]);
            }
        });

        RedirectNotification::success('Hearing has been updated.');

        $this->redirect(route('hearings.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.hearings.update-page', [
            'statuses' => HearingStatus::cases(),
        ]);
    }
}