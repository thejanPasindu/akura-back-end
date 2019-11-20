<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $fillable = [
        'user_id', 'role_id',    
    ];
    protected $table = 'user_role';
}
