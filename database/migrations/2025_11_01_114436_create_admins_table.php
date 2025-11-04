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
            $table->string('emailAdmins', 100);
            $table->string('password', 255);
            $table->tinyInteger('is_superAdmin')->default(0);
            $table->unsignedBigInteger('idOrganizerUnits');
            $table->timestamps();

            $table->foreign('idOrganizerUnits')->references('idOrganizerUnits')->on('tOrganizerUnits')->onDelete('cascade');
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
