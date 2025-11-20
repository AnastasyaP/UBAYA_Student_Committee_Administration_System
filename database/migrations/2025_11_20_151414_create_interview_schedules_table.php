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
        Schema::create('tInterviewSchedules', function (Blueprint $table) {
            $table->id('idInterviewSchedules');
            $table->date('date');
            $table->time('time');
            $table->string('place', 255);
            $table->string('link', 500);

            $table->unsignedBigInteger('idDivisions');
            $table->unsignedBigInteger('idCommittees');

            $table->timestamps();

            $table->foreign('idDivisions')->references('idDivisions')->on('tListDivisions')->onDelete('cascade');
            $table->foreign('idCommittees')->references('idCommittees')->on('tListDivisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tInterviewSchedules');
    }
};
