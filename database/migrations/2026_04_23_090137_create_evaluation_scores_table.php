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
        Schema::create('tEvaluationScores', function (Blueprint $table) {
            $table->id('idEvaluationScores');
            $table->unsignedBigInteger('idEvaluations')->nullable();
            $table->unsignedBigInteger('idEvaluationCriterias')->nullable();
            $table->double('score');
            $table->string('comment', 500)->nullable();

            $table->timestamps();

            $table->foreign('idEvaluations')->references('idEvaluations')->on('tEvaluations')->onDelete('cascade');
            $table->foreign('idEvaluationCriterias')->references('idEvaluationCriterias')->on('tEvaluationCriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tEvaluationScores');
    }
};
