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

#[Title('Create Resolution')]
class CreatePage extends Component
{
    public Dispute $dispute;

    public string $resolution_type = '';
    public string $details = '';
    public string $resolved_at = '';
    public ?int $hearing_id = null;

    public function mount(Dispute $dispute): void
    {
        $this->authorize('create', Resolution::class);

        if ($dispute->resolution) {
            $this->redirect(route('disputes.resolution.view', $dispute), navigate: true);
            return;
        }

        $this->dispute     = $dispute->load(['hearings', 'resolution']);
        $this->resolved_at = now()->format('Y-m-d\TH:i');
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
            Resolution::create([
                'dispute_id'      => $this->dispute->id,
                'hearing_id'      => $validated['hearing_id'],
                'resolution_type' => $validated['resolution_type'],
                'details'         => $validated['details'],
                'resolved_by'     => auth()->id(),
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

        RedirectNotification::success("Resolution for dispute \"{$this->dispute->case_number}\" has been recorded.");

        $this->redirect(route('disputes.resolution.view', $this->dispute), navigate: true);
    }

    public function render()
    {
        return view('livewire.resolutions.create-page', [
            'resolutionTypes' => ResolutionType::cases(),
        ]);
    }
}