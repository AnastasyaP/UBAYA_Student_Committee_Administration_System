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
        Schema::create('tCommittees', function (Blueprint $table) {
            $table->id('idCommittees');
            $table->string('emailAdmins', 100);
            $table->string('name', 45);
            $table->date('start_period')->nullable();
            $table->date('end_period')->nullable();
            $table->string('description', 600)->nullable();
            $table->string('requirements', 500)->nullable();
            $table->string('picture', 255)->nullable();
            $table->string('contact', 45)->nullable();
            $table->date('start_regis')->nullable();
            $table->date('end_regis')->nullable();
            $table->text('evaluation')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tCommittees');
    }
};
