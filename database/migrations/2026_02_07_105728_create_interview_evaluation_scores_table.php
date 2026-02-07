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
        Schema::create('tInterviewEvaluationScores', function (Blueprint $table) {
            $table->id('idInterviewEvaluationScores');
            $table->unsignedBigInteger('idInterviewEvaluations');
            $table->unsignedBigInteger('idInterviewCriterias');
            $table->integer('score');
            $table->longText('answer');

            $table->timestamps();

            $table->foreign('idInterviewEvaluations')->references('idInterviewEvaluations')->on('tInterviewEvaluations')->onDelete('cascade');
            $table->foreign('idInterviewCriterias')->references('idInterviewCriterias')->on('tInterviewCriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tInterviewEvaluationScores');
    }
};
