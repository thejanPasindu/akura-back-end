<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Notifications\CommonNotification;
use App\Events\CommNotification;
use Event;
use Notification;

class CommNotificationController extends Controller
{
    public function my(){
        //Notification::send(Auth::user(), new CommNotification());


        //return Auth::user()->unreadNotifications->markAsRead();;
        
    }

    public function loadNotification()
    {
        return Auth::user()->Notifications->take(5);
    }

    public function markNotification()
    {
        $data = ['id'=>1, 'msg'=>"heloo", 'type'=>'scholarship'];

        //Event::dispatch(new CommNotification($data));
        //Notification::send(Auth::user(), new CommonNotification($data));
        Auth::user()->unreadNotifications->markAsRead();
        //Auth::user()->notifications()->delete();
        return response()->json(["message"=>"successful"], 200);
    }
}
