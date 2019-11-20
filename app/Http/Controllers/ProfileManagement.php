<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Avatar;

use DB;

class ProfileManagement extends Controller
{
    //for get profile data
    public function getProfil(){
        // return 123;
        $myDetails = DB::table('users')
                    ->select('users.fname', 'users.mname', 'users.name', 'users.email', 'users.cnumber', 'users.no', 'users.street', 'users.city', 'users.dob', 'gender', 'position', 'occupation', 'paymentType', 'paymentAmount', 'medium', 'subject')
                    ->where('users.id','=',Auth::user()->id)
                    ->get();
        return $myDetails;
    }


    //for update profile data
    public function updateData(Request $request){
        $update_details = array(
            'email' => $request->email,
            'cnumber' => $request->cnumber,
            'paymentType' => $request->paymentType,
            'paymentAmount' => $request->paymentAmount,
            'occupation' => $request->occupation
        );
        
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update($update_details);

        return response()->json(['message' => 'Successfully updated!'], 200);
    }

    //for save avatar
    public function saveAvatar(Request $request){
       
        $avatar = new Avatar([
            'user_id' => Auth::user()->id,
            'avatar' => $request->avatar
        ]);
        $avatar->save();
        return response()->json(['message'=>'Successfully uploaded!'], 200);
    }

    //for get avatar
    public function getAvatar(){
       
        $avatar = DB::table('avatars')
                    ->select('*')
                    ->where('user_id','=',Auth::user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        
        return $avatar;
    }

    //for delete avatar
    public function deleteAvatar(){
       
        DB::delete('delete from avatars where user_id = ?',[Auth::user()->id]);

        return response()->json(['message'=>'Successfully deleted!'], 200);
    }

   
}
