<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projectaddresss extends Model
{
    protected $fillable = [
        'project_id', 'province', 'city', 'zip', 'addres_line_1', 'addres_line_2', 
    ];
    
    protected $table = 'projectaddresss';

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }
}
