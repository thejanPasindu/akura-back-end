<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MentorList extends Model
{
    protected $fillable = [
        'id', 'role_id',    
    ];
    protected $table = 'mentorsidlist';

    public function mentorList(){
        //$mentorId = DB::table('roles')->where('name', 'mentor')->value('id');
        //$userId = UserRoles::where('role_id','=',$mentorId);
        return $this -> belongsTo('App\User')->get();
    }

   
}
