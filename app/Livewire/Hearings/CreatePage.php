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

#[Title('Schedule Hearing')]
class CreatePage extends Component
{
    public ?int $dispute_id = null;
    public string $scheduled_date = '';
    public string $scheduled_time = '';
    public string $location = '';
    public string $status = '';
    public string $notes = '';
    public ?int $conducted_by = null;
    public array $attendees = [];

    public function mount(): void
    {
        $this->authorize('create', Hearing::class);

        $this->status         = HearingStatus::Scheduled->value;
        $this->scheduled_date = now()->format('Y-m-d');
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

    public function updatedDisputeId(): void
    {
        $this->loadAttendeesFromDispute();
    }

    private function loadAttendeesFromDispute(): void
    {
        if (! $this->dispute_id) {
            $this->attendees = [];
            return;
        }

        $dispute = Dispute::with('parties.person')->find($this->dispute_id);

        if (! $dispute) {
            $this->attendees = [];
            return;
        }

        // Pre-populate attendees from dispute parties
        $this->attendees = $dispute->parties->map(fn ($party) => [
            'person_id' => $party->person_id,
            'name'      => $party->person->full_name,
            'role'      => $party->role->value,
            'attended'  => false,
        ])->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $hearing = Hearing::create([
                'dispute_id'     => $validated['dispute_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'],
                'location'       => $validated['location'],
                'status'         => $validated['status'],
                'notes'          => $validated['notes'],
                'conducted_by'   => $validated['conducted_by'],
            ]);

            foreach ($validated['attendees'] ?? [] as $attendee) {
                HearingAttendee::create([
                    'hearing_id' => $hearing->id,
                    'person_id'  => $attendee['person_id'],
                    'attended'   => $attendee['attended'] ?? false,
                ]);
            }
        });

        RedirectNotification::success('Hearing has been scheduled.');

        $this->redirect(route('hearings.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.hearings.create-page', [
            'statuses' => HearingStatus::cases(),
        ]);
    }
}