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
        Schema::create('tRegistrations', function (Blueprint $table) {
            $table->id('idRegistrations');

            $table->unsignedBigInteger('idUsers');
            $table->unsignedBigInteger('idDivisions');
            $table->unsignedBigInteger('idCommittees');

            $table->enum('status', ['pending', 'accepted', 'rejected']);
            $table->enum('percentage', ['0','30','40','50','60','70','100']);
            $table->enum('position', ['bph', 'koor', 'anggota']);

            $table->unsignedBigInteger('idInterviewSchedules');
            
            $table->timestamps();

            $table->foreign('idUsers')->references('idUsers')->on('tUsers')->onDelete('cascade');
            $table->foreign('idDivisions')->references('idDivisions')->on('tDivisions')->onDelete('cascade');
            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('idInterviewSchedules')->references('idInterviewSchedules')->on('tInterviewSchedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tRegistrations');
    }
};
