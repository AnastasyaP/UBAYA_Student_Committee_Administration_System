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
        Schema::create('tAHPScores', function (Blueprint $table) {
            $table->id('idAHPScores');
            $table->unsignedBigInteger('idRegistrations');
            $table->unsignedBigInteger('idAHPCriterias');
            $table->double('raw_score');
            $table->double('normalized_score');
            $table->double('weighted_score');

            $table->timestamps();

            $table->foreign('idRegistrations')->references('idRegistrations')->on('tRegistrations')->onDelete('cascade');
            $table->foreign('idAHPCriterias')->references('idAHPCriterias')->on('tAHPCriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tAHPScores');
    }
};
