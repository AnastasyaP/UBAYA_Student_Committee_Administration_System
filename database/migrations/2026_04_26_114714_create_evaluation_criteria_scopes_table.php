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
        Schema::create('tEvaluationCriteriaScopes', function (Blueprint $table) {
            $table->id('idEvaluationCriteriaScopes');
            $table->unsignedBigInteger('idEvaluationCriterias');
            $table->unsignedBigInteger('idCommittees');
            $table->unsignedBigInteger('idDivisions')->nullable();

            $table->timestamps();

            $table->foreign('idEvaluationCriterias')->references('idEvaluationCriterias')->on('tEvaluationCriterias')->onDelete('cascade');
            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('idDivisions')->references('idDivisions')->on('tDivisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tEvaluationCriteriaScopes');
    }
};
