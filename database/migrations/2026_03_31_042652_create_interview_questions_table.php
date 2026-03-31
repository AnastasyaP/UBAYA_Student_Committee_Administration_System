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
        Schema::create('tInterviewQuestions', function (Blueprint $table) {
            $table->id('idInterviewQuestions');
            $table->string('question', 500);
            $table->integer('max_score');
            $table->unsignedBigInteger('idInterviewCriterias');
            
            $table->timestamps();

            $table->foreign('idInterviewQuestions')->references('idInterviewCriterias')->on('tInterviewCriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tInterviewQuestions');
    }
};
