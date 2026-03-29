<?php

declare(strict_types=1);

namespace App\Livewire\Disputes;

use App\Models\Dispute;
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
            'assignee',
            'parties.person',
            'blotterEntry',
            'hearings.conductor',
            'resolution',
        ]);
    }

    public function render()
    {
        return view('livewire.disputes.view-page');
    }
}