<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentSibling extends Model
{
    
    protected $fillable = [
        'user_id','sibling_name','sibling_relationship','school_university','grade_year',
    ];
}
