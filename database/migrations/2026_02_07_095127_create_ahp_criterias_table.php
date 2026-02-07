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
        Schema::create('tAHPCriterias', function (Blueprint $table) {
            $table->id('idAHPCriterias');
            $table->string('name');
            $table->unsignedBigInteger('idDivisions');

            $table->timestamps();

            $table->foreign('idDivisions')->references('idDivisions')->on('tDivisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tAHPCriterias');
    }
};
