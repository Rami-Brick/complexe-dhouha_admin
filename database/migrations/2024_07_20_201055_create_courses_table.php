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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('level', ['bébé', '1-2 ans', '2-3 ans', '3 ans', '4 ans', '5 ans']);
            $table->foreignId('staff_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('registration_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('scholarship_id')->constrained('products')->restrictOnDelete();
            $table->date('start_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
