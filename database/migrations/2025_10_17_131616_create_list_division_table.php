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
        Schema::create('tListDivisions', function (Blueprint $table) {
            $table->unsignedBigInteger('idDivisions');
            $table->unsignedBigInteger('idCommittees');

            $table->tinyInteger('is_open')->default(0);
            $table->string('description', 600);
            $table->string('picture', 255);
            $table->timestamps();

            $table->foreign('idCommittees')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('idDivisions')->references('idDivisions')->on('tDivisions')->onDelete('cascade');

            $table->primary(['idCommittees', 'idDivisions']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tListDivisions');
    }
};
