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
        Schema::create('tInterviewDivisionAHPCriterias', function (Blueprint $table) {
            $table->unsignedBigInteger('idInterviewCriterias');
            $table->unsignedBigInteger('idListDivisionAHPCriterias');

            $table->primary(['idInterviewCriterias', 'idListDivisionAHPCriterias']);

            $table->timestamps();

            $table->foreign('idInterviewCriterias', 'fk_interview_criteria')->references('idInterviewCriterias')->on('tInterviewCriterias')->onDelete('cascade');
            $table->foreign('idListDivisionAHPCriterias', 'fk_list_division_ahp')->references('idListDivisionAHPCriterias')->on('tListDivisionAHPCriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tInterviewDivisionAHPCriterias');
    }
};
