<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'semester',
        'school_year',
        'tuition_fee',
        'misc_fee',
        'total_amount',
        'status',
    ];

    /**
     * Get the student that owns the billing.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}