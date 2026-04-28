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
        Schema::create('tEvaluationCriterias', function (Blueprint $table) {
            $table->id('idEvaluationCriterias');
            $table->string('name', 255);
            $table->text('description');
            $table->enum('target_type', ['user', 'division', 'committee']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tEvaluationCriterias');
    }
};
