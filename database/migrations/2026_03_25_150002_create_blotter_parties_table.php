<?php

declare(strict_types=1);

use App\Enums\BlotterPartyRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotter_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blotter_id')->constrained('blotter_entries')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('persons');
            $table->enum('role', array_column(BlotterPartyRole::cases(), 'value'));

            $table->index('blotter_id');
            $table->index('person_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotter_parties');
    }
};
