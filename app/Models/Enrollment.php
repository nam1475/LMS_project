<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    // Add this property:
    protected $fillable = [
        'user_id',
        'course_id',
        'instructor_id',
        'have_access',
        'have_access'
    ];

    function course() : BelongsTo {
       return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
