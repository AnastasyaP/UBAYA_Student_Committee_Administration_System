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
        Schema::create('tInterviewEvaluations', function (Blueprint $table) {
            $table->id('idInterviewEvaluations');
            $table->unsignedBigInteger('idEvaluator');
            $table->unsignedBigInteger('idRegistrations');
            $table->string('comment', 255);

            $table->timestamps();

            $table->foreign('idEvaluator')->references('idUsers')->on('tUsers')->onDelete('cascade');
            $table->foreign('idRegistrations')->references('idRegistrations')->on('tRegistrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tInterviewEvaluations');
    }
};
