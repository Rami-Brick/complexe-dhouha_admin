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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('gender', ['boy', 'girl']);
            $table->foreignId('relative_id')->nullable()->constrained()->nullOnDelete();
            $table->string('products')->nullable();
            $table->date('start_date')->nullable();
            $table->enum('payment_status',['Paid','Overdue','Partial'])->nullable();
            $table->text('comments')->nullable();
            $table->string('event_participation')->nullable();
            $table->string('leave_with')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
