<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUploadModel extends Model
{
    //
    protected $fillable = [
        'student_id', 'acadamic_year','semester', 'school_grade', 'term', 'path'
    ];

    protected $table = "reportupload";
}
