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
            $table->id('student_id'); // Auto-incrementing primary key, named student_id
            $table->string('full_name'); // Student's full name
            $table->string('email')->unique()->nullable(); // Unique email, can be null
            $table->string('contact_number')->nullable(); // Contact number, can be null
            $table->string('course')->nullable(); // Course, can be null
            $table->string('year_level')->nullable(); // Year level, can be null
            $table->timestamps(); // created_at and updated_at columns
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