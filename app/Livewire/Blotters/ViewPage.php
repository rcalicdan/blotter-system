<?php

declare(strict_types=1);

namespace App\Livewire\Blotters;

use App\Models\BlotterEntry;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('View Blotter Entry')]
class ViewPage extends Component
{
    public BlotterEntry $blotterEntry;

    public function mount(BlotterEntry $blotterEntry): void
    {
        $this->authorize('view', $blotterEntry);

        $this->blotterEntry = $blotterEntry->load(['recorder', 'parties.person', 'dispute']);
    }

    public function render()
    {
        return view('livewire.blotters.view-page');
    }
}