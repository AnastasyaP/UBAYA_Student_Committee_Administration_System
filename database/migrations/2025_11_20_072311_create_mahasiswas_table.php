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
        Schema::create('tMahasiswas', function (Blueprint $table) {
            $table->id('idMahasiswas');
            $table->string('nrp', 45);
            $table->string('cv', 255);
            $table->string('portofolio', 255);
            $table->unsignedBigInteger('idUsers');
            $table->timestamps();

            $table->foreign('idUsers')->references('idUsers')->on('tUsers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tMahasiswas');
    }
};
