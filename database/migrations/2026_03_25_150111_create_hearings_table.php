<?php

declare(strict_types=1);

use App\Enums\HearingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained('disputes')->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->string('location', 255)->nullable();
            $table->enum('status', array_column(HearingStatus::cases(), 'value'))->default(HearingStatus::Scheduled->value);
            $table->text('notes')->nullable();
            $table->foreignId('conducted_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('dispute_id');
            $table->index('scheduled_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hearings');
    }
};
