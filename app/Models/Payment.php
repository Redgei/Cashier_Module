<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str; // Import Str facade for string manipulation

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'full_name',
        'billing_id',
        'total_amount',
        'amount',
        'balance',
        'payment_method',
        'status',
        'paid_at',
        'receipt_number', // Add receipt_number to fillable
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($payment) {
            // Generate a unique receipt number before creating the payment
            if (empty($payment->receipt_number)) {
                $payment->receipt_number = (string) Str::uuid(); // Using UUID for uniqueness
            }
        });
    }

    /**
     * Get the student that owns the payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get the billing that the payment belongs to.
     */
    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class);
    }
} 