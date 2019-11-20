<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'creater_id',
        'project_name',
        'project_manager_id',
        'project_coordinator1', 
        'project_coordinator2',
        'start_date',
        'end_date',
        'location',
        'district', 
        'city',
        'addres_line_1',
        'addres_line_2',
        'zip',
        'type',
        'description',
    ];

    public function ProjectAddresss()
    {
        return $this->hasOne('App\Projectaddresss', 'project_id');
    }
}
