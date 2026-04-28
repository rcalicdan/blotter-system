<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\BlotterStatus;
use App\Enums\DisputeStatus;
use App\Enums\HearingStatus;
use App\Models\BlotterEntry;
use App\Models\Dispute;
use App\Models\Hearing;
use App\Models\Person;
use App\Models\Resolution;
use App\Models\Officer;
use App\Models\Judge;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard',[
            'totalBlotters'    => BlotterEntry::count(),
            'openBlotters'     => BlotterEntry::where('status', BlotterStatus::Open)->count(),
            'closedBlotters'   => BlotterEntry::where('status', BlotterStatus::Closed)->count(),
            'referredBlotters' => BlotterEntry::where('status', BlotterStatus::Referred)->count(),
            
            'totalDisputes'     => Dispute::count(),
            'filedDisputes'     => Dispute::where('status', DisputeStatus::Filed)->count(),
            'ongoingDisputes'   => Dispute::where('status', DisputeStatus::Ongoing)->count(),
            'settledDisputes'   => Dispute::where('status', DisputeStatus::Settled)->count(),
            'dismissedDisputes' => Dispute::where('status', DisputeStatus::Dismissed)->count(),
            'escalatedDisputes' => Dispute::where('status', DisputeStatus::Escalated)->count(),
            
            'totalHearings'     => Hearing::count(),
            'scheduledHearings' => Hearing::where('status', HearingStatus::Scheduled)->count(),
            'completedHearings' => Hearing::where('status', HearingStatus::Completed)->count(),
            'cancelledHearings' => Hearing::where('status', HearingStatus::Cancelled)->count(),
            
            'totalResolutions' => Resolution::count(),
            'totalPeople'      => Person::count(),
            
            'totalCriminals'   => Person::where('is_criminal', true)->count(),
            'activeOfficers'   => Officer::where('status', 'Active')->count(),
            'activeJudges'     => Judge::where('status', 'Active')->count(),

            'recentBlotters' => BlotterEntry::with('recorder')
                ->latest()
                ->take(5)
                ->get(),

            'recentDisputes' => Dispute::with(['filer', 'officer']) 
                ->latest()
                ->take(5)
                ->get(),

            'upcomingHearings' => Hearing::with(['dispute', 'judge'])
                ->where('status', HearingStatus::Scheduled)
                ->where('scheduled_date', '>=', now()->toDateString())
                ->orderBy('scheduled_date')
                ->orderBy('scheduled_time')
                ->take(5)
                ->get(),
        ]);
    }
}