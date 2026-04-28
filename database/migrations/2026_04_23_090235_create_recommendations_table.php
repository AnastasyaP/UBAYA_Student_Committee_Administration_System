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
        Schema::create('tRecommendations', function (Blueprint $table) {
            $table->id('idRecommendations');
            $table->unsignedBigInteger('idUsers')->nullable();
            $table->unsignedBigInteger('idCommittees')->nullable();
            $table->double('predicted_score');

            $table->timestamps();

            $table->foreign('idUsers')->references('idUsers')->on('tUsers')->onDelete('cascade');
            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tRecommendations');
    }
};
