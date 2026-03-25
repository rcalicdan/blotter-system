<?php

use App\Enums\DisputeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->string('case_number', 30)->unique();
            $table->foreignId('blotter_id')->nullable()->constrained('blotter_entries')->nullOnDelete();
            $table->string('subject', 255);
            $table->text('description')->nullable();
            $table->enum('status', array_column(DisputeStatus::cases(), 'value'))->default(DisputeStatus::Filed->value);
            $table->foreignId('filed_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('status');
            $table->index('assigned_to');
            $table->index('blotter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};