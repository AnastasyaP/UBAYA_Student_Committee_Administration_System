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
        Schema::create('tAdmins', function (Blueprint $table) {
            $table->id('idAdmins');
            $table->tinyInteger('is_superAdmin')->default(0);
            $table->unsignedBigInteger('idOrganizerUnits');
            $table->unsignedBigInteger('idUsers');
            $table->timestamps();

            $table->foreign('idOrganizerUnits')->references('idOrganizerUnits')->on('tOrganizerUnits')->onDelete('cascade');
            $table->foreign('idUsers')->references('idUsers')->on('tUsers')->conDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tAdmins');
    }
};
