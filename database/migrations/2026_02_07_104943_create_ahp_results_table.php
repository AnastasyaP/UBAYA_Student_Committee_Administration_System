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
        Schema::create('tAHPResults', function (Blueprint $table) {
            $table->id('idAHPResults');
            $table->unsignedBigInteger('idRegistrations');
            $table->double('final_score');

            $table->timestamps();

            $table->foreign('idRegistrations')->references('idRegistrations')->on('tRegistrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tAHPResults');
    }
};
