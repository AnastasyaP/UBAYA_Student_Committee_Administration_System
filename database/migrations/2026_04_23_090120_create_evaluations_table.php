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
        Schema::create('tEvaluations', function (Blueprint $table) {
            $table->id('idEvaluations');
            $table->unsignedBigInteger('evaluator_id');
            $table->unsignedBigInteger('target_committee')->nullable();
            $table->unsignedBigInteger('target_division')->nullable();
            $table->unsignedBigInteger('target_user')->nullable();
            $table->longText('comment');
            $table->double('ratings');

            $table->timestamps();

            $table->foreign('evaluator_id')->references('idUsers')->on('tUsers')->onDelete('cascade');
            $table->foreign('target_committee')->references('idCommittees')->on('tCommittees')->onDelete('cascade');
            $table->foreign('target_division')->references('idDivisions')->on('tDivisions')->onDelete('cascade');
            $table->foreign('target_user')->references('idUsers')->on('tUsers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tEvaluations');
    }
};
