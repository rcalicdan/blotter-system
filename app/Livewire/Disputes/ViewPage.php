<?php

declare(strict_types=1);

namespace App\Livewire\Disputes;

use App\Models\Dispute;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('View Dispute')]
class ViewPage extends Component
{
    public Dispute $dispute;

    public function mount(Dispute $dispute): void
    {
        $this->authorize('view', $dispute);

        $this->dispute = $dispute->load([
            'filer',
            'officer',
            'parties.person',
            'blotterEntry',
            'hearings.judge',
            'resolution',
        ]);
    }

    public function deleteResolution(): void
    {
        $this->authorize('delete', $this->dispute->resolution);

        $this->dispute->resolution->delete();

        $this->dispute = $this->dispute->fresh([
            'filer',
            'assignee',
            'parties.person',
            'blotterEntry',
            'hearings.conductor',
            'resolution',
        ]);

        $this->dispatch('notify', message: 'Resolution has been deleted.', type: 'success');
    }

    public function render()
    {
        return view('livewire.disputes.view-page');
    }
}
