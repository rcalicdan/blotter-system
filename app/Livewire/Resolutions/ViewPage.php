<?php

declare(strict_types=1);

namespace App\Livewire\Resolutions;

use App\Models\Dispute;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Resolution')]
class ViewPage extends Component
{
    public Dispute $dispute;

    public function mount(Dispute $dispute): void
    {
        if (! $dispute->resolution) {
            $this->redirect(route('disputes.view', $dispute), navigate: true);
            return;
        }

        $this->authorize('view', $dispute->resolution);

        $this->dispute = $dispute->load([
            'resolution.resolver',
            'resolution.hearing',
            'parties.person',
        ]);
    }

    public function render()
    {
        return view('livewire.resolutions.view-page');
    }
}