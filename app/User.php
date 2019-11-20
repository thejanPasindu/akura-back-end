<?php

namespace App;

use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $dates = ['deleted_at'];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }

    public function hasAnyRole($roles){
        if(is_array($roles)){
            foreach($roles as $role){
                if($this->hasRole($role)){
                    return true;
                }
            }
        }else{
            if($this->hasRole($roles)){
                return true;
            }
        }
        return false;
    }

    public function hasRole($role){
        if($this->roles()->where('name',$role)->first()){
            return true;
        }
        return false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'activation_token','fname','mname', 'cnumber', 'no', 'street', 'city', 'dob', 'gender', 'position', 'occupation', 'registrationType', 'studingYear', 'studingSemester', 'paymentType', 'paymentAmount', 'medium', 'subject',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function mentorOfMine(){
            return $this->belongsToMany('App\User', 'mentors', 'student_id', 'mentor_id');//->where('status','=', true);
    }

    public function mentorOf(){
        return $this->belongsToMany('App\User', 'mentors', 'mentor_id', 'student_id');
    }

    public function mentor(){
        return $this->mentorOfMine;//->merge($this->mentorOf);
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }
   
    // public function adminList(){
        
    // }

    public function receivesBroadcastNotificationsOn()
    {
        //return 'users.'.$this->id;

        return new Channel('commMsg.'.$this->$id);
    }

}
