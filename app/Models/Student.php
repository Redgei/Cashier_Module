<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $primaryKey = 'student_id'; // student_id is the primary key
    public $incrementing = true; // student_id is auto-incrementing
    protected $keyType = 'int'; // student_id is an integer type

    protected $fillable = [
        'full_name',
        'email',
        'contact_number',
        'course',
        'year_level',
    ];

    /**
     * Get the payments for the student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id', 'student_id');
    }
}