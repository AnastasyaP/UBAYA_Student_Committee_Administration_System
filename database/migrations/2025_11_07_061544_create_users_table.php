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
        Schema::create('tUsers', function (Blueprint $table) {
            $table->id('idUsers');
            $table->string('email', 100);
            $table->string('nrp', 45);
            $table->string('password', 100);
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('cv', 255);
            $table->string('portofolio', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tUsers');
    }
};
