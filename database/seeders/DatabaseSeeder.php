<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BlotterPartyRole;
use App\Enums\BlotterStatus;
use App\Enums\DisputePartyRole;
use App\Enums\DisputeStatus;
use App\Enums\HearingStatus;
use App\Enums\ResolutionType;
use App\Enums\UserRole;
use App\Models\BlotterEntry;
use App\Models\BlotterParty;
use App\Models\CriminalRecord;
use App\Models\Dispute;
use App\Models\DisputeParty;
use App\Models\Hearing;
use App\Models\HearingAttendee;
use App\Models\Judge;
use App\Models\Officer;
use App\Models\Person;
use App\Models\Resolution;
use App\Models\User;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    private array $taclobanLocations =[
        'San Jose, Tacloban City',
        'Marasbaras, Tacloban City',
        'V&G Subdivision, Tacloban City',
        'Paterno St, Tacloban City',
        'Gomez St, Tacloban City',
        'Sagkahan, Tacloban City',
        'Barangay 109-A, Tacloban City',
        'Fatima Village, Tacloban City',
    ];

    public function run(): void
    {
        $faker = Faker::create('en_PH');

        $this->seedUsers($faker);
        $this->seedOfficers($faker);
        $this->seedJudges($faker);
        $this->seedPeople($faker);
        $this->seedCriminalRecords($faker);
        $this->seedBlotters($faker);
        $this->seedDisputesAndHearings($faker);
    }

    private function seedUsers(Generator $faker): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@justice.com',
            'password' => bcrypt('password123'),
            'role' => UserRole::SuperAdmin->value,
        ]);

        for ($i = 0; $i < 5; $i++) {
            User::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password123'),
                'role' => UserRole::Staff->value,
            ]);
        }
    }

    private function seedOfficers(Generator $faker): void
    {
        $ranks =['PCpl', 'PSSg', 'PMSg', 'PEMS', 'PCPT', 'PMAJ'];

        for ($i = 0; $i < 10; $i++) {
            Officer::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'badge_number' => $faker->bothify('#####'),
                'rank' => $faker->randomElement($ranks),
                'status' => $faker->randomElement(['Active', 'Active', 'Active', 'Inactive']),
            ]);
        }
    }

    private function seedJudges(Generator $faker): void
    {
        $branches =['Branch 6', 'Branch 7', 'Branch 8', 'Branch 9', 'Branch 34'];

        for ($i = 0; $i < 5; $i++) {
            Judge::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'court_branch' => 'RTC ' . $faker->randomElement($branches),
                'status' => 'Active',
            ]);
        }
    }

    private function seedPeople(Generator $faker): void
    {
        for ($i = 0; $i < 60; $i++) {
            Person::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'birthdate' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'address' => $faker->randomElement($this->taclobanLocations),
                'contact_number' => '+639' . $faker->numerify('#########'),
                'is_criminal' => false,
            ]);
        }
    }

    private function seedCriminalRecords(Generator $faker): void
    {
        $charges =['Theft', 'Physical Injuries', 'Estafa', 'Vandalism', 'Trespassing', 'Grave Threats', 'Alarms and Scandals'];
        $statuses =['Arrested', 'Wanted', 'Convicted', 'Cleared'];
        
        $offenders = Person::inRandomOrder()->take(15)->get();

        foreach ($offenders as $offender) {
            $offender->update(['is_criminal' => true]);

            CriminalRecord::create([
                'person_id' => $offender->id,
                'charge' => $faker->randomElement($charges),
                'date_committed' => $faker->dateTimeBetween('-5 years', '-1 month')->format('Y-m-d'),
                'status' => $faker->randomElement($statuses),
                'notes' => $faker->paragraph(),
            ]);

            if ($faker->boolean(30)) {
                CriminalRecord::create([
                    'person_id' => $offender->id,
                    'charge' => $faker->randomElement($charges),
                    'date_committed' => $faker->dateTimeBetween('-10 years', '-6 years')->format('Y-m-d'),
                    'status' => 'Cleared',
                    'notes' => $faker->paragraph(),
                ]);
            }
        }
    }

    private function seedBlotters(Generator $faker): void
    {
        $userIds = User::pluck('id')->toArray();
        $personIds = Person::pluck('id')->toArray();

        $narratives =[
            "Complainant reported that their neighbor was playing excessively loud music until 3 AM and refused to lower the volume when confronted.",
            "A minor physical altercation occurred between the parties regarding a parking space dispute outside their residences.",
            "Complainant stated that their pet dog was allegedly poisoned by the respondent after a series of arguments.",
            "Respondent allegedly took construction materials from the complainant's unfinished property without permission.",
            "Verbal threats were exchanged during a barangay assembly meeting concerning waste disposal."
        ];

        for ($i = 1; $i <= 30; $i++) {
            $blotter = BlotterEntry::create([
                'blotter_number' => sprintf('BLT-%d-%04d', now()->year, $i),
                'incident_date' => clone $faker->dateTimeBetween('-6 months', '-1 week'),
                'incident_time' => $faker->time('H:i'),
                'incident_location' => $faker->randomElement($this->taclobanLocations),
                'narrative' => $faker->randomElement($narratives),
                'status' => $faker->randomElement(array_column(BlotterStatus::cases(), 'value')),
                'recorded_by' => $faker->randomElement($userIds),
            ]);

            $this->createBlotterParties($blotter, $faker->randomElements($personIds, 3));
        }
    }

    private function createBlotterParties(BlotterEntry $blotter, array $selectedPeople): void
    {
        $roles =[
            BlotterPartyRole::Complainant->value,
            BlotterPartyRole::Respondent->value,
            BlotterPartyRole::Witness->value
        ];

        foreach ($roles as $index => $role) {
            BlotterParty::create([
                'blotter_id' => $blotter->id,
                'person_id' => $selectedPeople[$index],
                'role' => $role,
            ]);
        }
    }

    private function seedDisputesAndHearings(Generator $faker): void
    {
        $userIds = User::pluck('id')->toArray();
        $officerIds = Officer::pluck('id')->toArray();
        
        $subjects =[
            'Property Boundary Dispute',
            'Unpaid Debt of PHP 50,000',
            'Malicious Mischief and Vandalism',
            'Breach of Verbal Contract',
            'Slander and Defamation'
        ];

        $blotters = BlotterEntry::inRandomOrder()->take(20)->get();
        $disputeCounter = 1;

        foreach ($blotters as $blotter) {
            $status = $faker->randomElement(array_column(DisputeStatus::cases(), 'value'));
            
            $dispute = Dispute::create([
                'case_number' => sprintf('DSP-%d-%04d', now()->year, $disputeCounter++),
                'blotter_id' => $blotter->id,
                'subject' => $faker->randomElement($subjects),
                'description' => $faker->realText(),
                'status' => $status,
                'filed_by' => $faker->randomElement($userIds),
                'officer_id' => $faker->randomElement($officerIds),
                'created_at' => Carbon::parse($blotter->incident_date)->addDays(rand(1, 5)),
            ]);

            $this->createDisputeParties($dispute, $blotter);

            if (in_array($status,[DisputeStatus::Ongoing->value, DisputeStatus::Settled->value, DisputeStatus::Dismissed->value])) {
                $this->createHearingAndResolution($faker, $dispute, $status);
            }
        }
    }

    private function createDisputeParties(Dispute $dispute, BlotterEntry $blotter): void
    {
        $complainant = $blotter->parties()->where('role', BlotterPartyRole::Complainant->value)->first();
        $respondent = $blotter->parties()->where('role', BlotterPartyRole::Respondent->value)->first();

        DisputeParty::create([
            'dispute_id' => $dispute->id,
            'person_id' => $complainant->person_id,
            'role' => DisputePartyRole::Complainant->value,
        ]);

        DisputeParty::create([
            'dispute_id' => $dispute->id,
            'person_id' => $respondent->person_id,
            'role' => DisputePartyRole::Respondent->value,
        ]);
    }

    private function createHearingAndResolution(Generator $faker, Dispute $dispute, string $disputeStatus): void
    {
        $judgeIds = Judge::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        $hearingDate = Carbon::parse($dispute->created_at)->addDays(rand(7, 14));
        $hearingStatus = $disputeStatus === DisputeStatus::Ongoing->value ? HearingStatus::Scheduled->value : HearingStatus::Completed->value;

        $hearing = Hearing::create([
            'dispute_id' => $dispute->id,
            'scheduled_date' => $hearingDate->format('Y-m-d'),
            'scheduled_time' => $faker->randomElement(['09:00:00', '10:00:00', '14:00:00', '15:30:00']),
            'location' => 'Barangay Hall Arbitration Room',
            'status' => $hearingStatus,
            'notes' => $hearingStatus === HearingStatus::Completed->value ? $faker->paragraph() : null,
            'judge_id' => $faker->randomElement($judgeIds),
            'created_at' => $dispute->created_at,
        ]);

        $complainant = $dispute->parties()->where('role', DisputePartyRole::Complainant->value)->first();
        $respondent = $dispute->parties()->where('role', DisputePartyRole::Respondent->value)->first();

        HearingAttendee::create([
            'hearing_id' => $hearing->id,
            'person_id' => $complainant->person_id,
            'attended' => $hearingStatus === HearingStatus::Completed->value,
        ]);

        HearingAttendee::create([
            'hearing_id' => $hearing->id,
            'person_id' => $respondent->person_id,
            'attended' => $hearingStatus === HearingStatus::Completed->value ? $faker->boolean(90) : false,
        ]);

        if (in_array($disputeStatus,[DisputeStatus::Settled->value, DisputeStatus::Dismissed->value])) {
            $resolutionType = $disputeStatus === DisputeStatus::Settled->value ? ResolutionType::Settled->value : ResolutionType::Dismissed->value;
            
            Resolution::create([
                'dispute_id' => $dispute->id,
                'hearing_id' => $hearing->id,
                'resolution_type' => $resolutionType,
                'details' => $faker->paragraph(),
                'resolved_by' => $faker->randomElement($userIds),
                'resolved_at' => $hearingDate->addHours(2),
            ]);
        }
    }
}