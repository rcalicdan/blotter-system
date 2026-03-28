<?php

declare(strict_types=1);

use App\Enums\ResolutionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispute_id')->constrained('disputes')->cascadeOnDelete();
            $table->foreignId('hearing_id')->nullable()->constrained('hearings')->nullOnDelete();
            $table->enum('resolution_type', array_column(ResolutionType::cases(), 'value'));
            $table->text('details')->nullable();
            $table->foreignId('resolved_by')->constrained('users');
            $table->timestamp('resolved_at')->useCurrent();

            $table->index('dispute_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolutions');
    }
};
