<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admission_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_criterion_id')->constrained()->onDelete('cascade');
            $table->string('subject_name');
            $table->float('required_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_scores');
    }
};
