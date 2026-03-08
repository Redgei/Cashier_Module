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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('full_name'); // Storing full_name directly as requested
            $table->unsignedBigInteger('billing_id')->nullable(); // Link to billings table, can be nullable
            $table->decimal('total_amount', 10, 2); // Total amount of the billing/fee
            $table->decimal('amount', 10, 2); // Amount paid in this transaction
            $table->decimal('balance', 10, 2)->nullable(); // Remaining balance after this payment
            $table->string('payment_method');
            $table->timestamp('paid_at')->nullable();
            $table->string('receipt_number')->unique(); // Auto-generated unique receipt number
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('billing_id')->references('id')->on('billings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};