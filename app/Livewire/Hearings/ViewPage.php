<?php

declare(strict_types=1);

namespace App\Livewire\Hearings;

use App\Models\Hearing;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('View Hearing')]
class ViewPage extends Component
{
    public Hearing $hearing;

    public function mount(Hearing $hearing): void
    {
        $this->authorize('view', $hearing);

        $this->hearing = $hearing->load([
            'dispute.parties.person',
            'conductor',
            'attendees.person',
            'resolution',
        ]);
    }

    public function render()
    {
        return view('livewire.hearings.view-page');
    }
}