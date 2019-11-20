<?php

namespace App;

use App\Events\MentorStudentMsgBroadcast;
use App\Events\MsgBroadcast;
use Illuminate\Database\Eloquent\Model;

class MentorStudentMsg extends Model
{

    protected $dispatchesEvents = [
        'created' => MentorStudentMsgBroadcast::class,
       // 'created' => MsgBroadcast::class 
    ];

    protected $fillable = [
        'sender_id', 'receiver_id', 'message', 'read', 'status',
    ];
}
