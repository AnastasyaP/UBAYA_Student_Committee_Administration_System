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
        Schema::create('tUserSimilarities', function (Blueprint $table) {
            $table->id('idUserSimilarities');
            $table->unsignedBigInteger('idUsers1')->nullable();
            $table->unsignedBigInteger('idUsers2')->nullable();
            $table->double('similarity_score');

            $table->timestamps();

            $table->foreign('idUsers1')->references('idUsers')->on('tUsers')->onDelete('cascade');
            $table->foreign('idUsers2')->references('idUsers')->on('tUsers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tUserSimilarities');
    }
};
