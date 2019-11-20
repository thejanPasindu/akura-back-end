<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentScholarship extends Model
{
    protected $fillable = [
        'student_id',
        'sponsor_id',
        'amount',
        'confirmed',
    ];
}
