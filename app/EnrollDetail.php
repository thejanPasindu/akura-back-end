<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnrollDetail extends Model
{
    protected $fillable = [
        'project_id','user_id'
    ];
}
