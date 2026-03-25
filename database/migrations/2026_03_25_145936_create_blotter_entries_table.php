<?php

use App\Enums\BlotterStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotter_entries', function (Blueprint $table) {
            $table->id();
            $table->string('blotter_number', 30)->unique();
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->text('incident_location');
            $table->text('narrative');
            $table->enum('status', array_column(BlotterStatus::cases(), 'value'))->default(BlotterStatus::Open->value);
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();

            $table->index('status');
            $table->index('incident_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotter_entries');
    }
};