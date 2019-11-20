<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUploadRem extends Model
{
    protected $fillable = [
        'student_id',
        'grade',
        'term',
        'level',
        'semester',
    ];
}
