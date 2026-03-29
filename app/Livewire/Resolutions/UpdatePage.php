<?php

declare(strict_types=1);

namespace App\Livewire\Resolutions;

use App\Enums\DisputeStatus;
use App\Enums\ResolutionType;
use App\Models\Dispute;
use App\Models\Resolution;
use App\Services\RedirectNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Resolution')]
class UpdatePage extends Component
{
    public Dispute $dispute;
    public Resolution $resolution;

    public string $resolution_type = '';
    public string $details = '';
    public string $resolved_at = '';
    public ?int $hearing_id = null;

    public function mount(Dispute $dispute): void
    {
        if (! $dispute->resolution) {
            $this->redirect(route('disputes.view', $dispute), navigate: true);
            return;
        }

        $this->authorize('update', $dispute->resolution);

        $this->dispute        = $dispute->load(['hearings', 'resolution']);
        $this->resolution     = $dispute->resolution;
        $this->resolution_type = $this->resolution->resolution_type->value;
        $this->details        = $this->resolution->details ?? '';
        $this->resolved_at    = $this->resolution->resolved_at->format('Y-m-d\TH:i');
        $this->hearing_id     = $this->resolution->hearing_id;
    }

    protected function rules(): array
    {
        return [
            'resolution_type' => ['required', 'string', 'in:'.implode(',', array_column(ResolutionType::cases(), 'value'))],
            'details'         => ['nullable', 'string'],
            'resolved_at'     => ['required', 'date'],
            'hearing_id'      => ['nullable', 'exists:hearings,id'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $this->resolution->update([
                'hearing_id'      => $validated['hearing_id'],
                'resolution_type' => $validated['resolution_type'],
                'details'         => $validated['details'],
                'resolved_at'     => $validated['resolved_at'],
            ]);

            $this->dispute->update([
                'status' => match(ResolutionType::from($validated['resolution_type'])) {
                    ResolutionType::Settled   => DisputeStatus::Settled->value,
                    ResolutionType::Dismissed => DisputeStatus::Dismissed->value,
                    ResolutionType::Escalated => DisputeStatus::Escalated->value,
                },
            ]);
        });

        RedirectNotification::success("Resolution for dispute \"{$this->dispute->case_number}\" has been updated.");

        $this->redirect(route('disputes.resolution.view', $this->dispute), navigate: true);
    }

    public function render()
    {
        return view('livewire.resolutions.update-page', [
            'resolutionTypes' => ResolutionType::cases(),
        ]);
    }
}