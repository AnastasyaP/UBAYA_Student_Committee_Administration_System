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
        Schema::create('tCommitteeOrganizers', function (Blueprint $table) {
            $table->unsignedBigInteger('idCommittees');
            $table->unsignedBigInteger('idOrganizerUnits');
            $table->timestamps();

            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('idOrganizerUnits')->references('idOrganizerUnits')->on('tOrganizerUnits')->onDelete('cascade');

            $table->primary(['idCommittees', 'idOrganizerUnits']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_organizer');
    }
};
