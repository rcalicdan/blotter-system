<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->boolean('is_criminal')->default(false)->after('contact_number');
        });

        Schema::table('disputes', function (Blueprint $table) {
            $table->dropIndex(['assigned_to']); 
            
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
            
            $table->foreignId('officer_id')->nullable()->after('filed_by')->constrained('officers')->nullOnDelete();
        });

        Schema::table('hearings', function (Blueprint $table) {
            $table->dropForeign(['conducted_by']);
            $table->dropColumn('conducted_by');
            
            $table->foreignId('judge_id')->nullable()->after('notes')->constrained('judges')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hearings', function (Blueprint $table) {
            $table->dropForeign(['judge_id']);
            $table->dropColumn('judge_id');
            $table->foreignId('conducted_by')->nullable()->constrained('users');
        });

        Schema::table('disputes', function (Blueprint $table) {
            $table->dropForeign(['officer_id']);
            $table->dropColumn('officer_id');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->index('assigned_to'); 
        });

        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('is_criminal');
        });
    }
};