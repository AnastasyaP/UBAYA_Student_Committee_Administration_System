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
        Schema::create('tUserPreferences', function (Blueprint $table) {
            $table->id('idUserPreferences');
            $table->unsignedBigInteger('idUsers');
            $table->unsignedBigInteger('idKeywords');

            $table->timestamps();

            $table->foreign('idUsers')->references('idUsers')->on('tUsers')->onDelete('cascade');
            $table->foreign('idKeywords')->references('idKeywords')->on('tKeywords')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tUserPreferences');
    }
};
