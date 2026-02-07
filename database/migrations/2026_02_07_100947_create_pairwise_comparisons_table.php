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
        Schema::create('tPairwiseComparisons', function (Blueprint $table) {
            $table->id('idPairwiseComparisons');
            $table->unsignedBigInteger('idCommittees');
            $table->unsignedBigInteger('idCriteria1');
            $table->unsignedBigInteger('idCriteria2');
            $table->double('weight');
            $table->unsignedBigInteger('idDivisions');
            
            $table->timestamps();

            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('idCriteria1')->references('idAHPCriterias')->on('tAHPCriterias')->onDelete('cascade');
            $table->foreign('idCriteria2')->references('idAHPCriterias')->on('tAHPCriterias')->onDelete('cascade');
            $table->foreign('idDivisions')->references('idDivisions')->on('tDivisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tPairwiseComparisons');
    }
};
