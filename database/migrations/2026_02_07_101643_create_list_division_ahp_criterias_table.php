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
        Schema::create('tListDivisionAHPCriterias', function (Blueprint $table) {
            $table->id('idListDivisionAHPCriterias');
            $table->unsignedBigInteger('idDivisions');
            $table->unsignedBigInteger('idCommittees');
            $table->unsignedBigInteger('idAHPCriterias');
            $table->double('average_weight');

            $table->timestamps();

            $table->foreign('idDivisions')->references('idDivisions')->on('tListDivisions')->onDelete('cascade');
            $table->foreign('idCommittees')->references('idCommittees')->on('tListDivisions')->onDelete('cascade');
            $table->foreign('idAHPCriterias')->references('idAHPCriterias')->on('tAHPCriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tListDivisionAHPCriterias');
    }
};
