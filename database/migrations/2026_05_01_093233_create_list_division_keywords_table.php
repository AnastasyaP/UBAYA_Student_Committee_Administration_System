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
        Schema::create('tListDivisionKeywords', function (Blueprint $table) {
            $table->unsignedBigInteger('idCommittees');
            $table->unsignedBigInteger('idDivisions');
            $table->unsignedBigInteger('idKeywords');

            $table->primary(['idCommittees', 'idDivisions', 'idKeywords']);

            $table->timestamps();

            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('idDivisions')->references('idDivisions')->on('tDivisions')->onDelete('cascade');
            $table->foreign('idKeywords')->references('idKeywords')->on('tKeywords')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tListDivisionKeywords');
    }
};
