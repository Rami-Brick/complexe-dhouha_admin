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
        Schema::create('relatives', function (Blueprint $table) {
            $table->id();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('phone_father')->nullable();
            $table->string('phone_mother')->nullable();
            $table->string('job_father')->nullable();
            $table->string('job_mother')->nullable();
            $table->string('cin_father')->nullable();
            $table->string('cin_mother')->nullable();
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
