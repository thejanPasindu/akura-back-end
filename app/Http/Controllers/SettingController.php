<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\ProjectType;
use App\User;
use Illuminate\Support\Facades\Auth;


class SettingController extends Controller
{
    //For get all project types in the database
    public function getProjectType(){
        $sql = "SELECT id,type FROM project_types";
        $projectType = DB::select(DB::raw($sql));
        return $projectType;
    }


    //For edit project types
    public function editProject(Request $request){

        if($request->action == 'Delete'){
            DB::delete('delete from project_types where id = ?',[$request->id]);
            return response()->json(['message'=>'Successfully deleted.!','status'=>97],200);
        }
        elseif($request->action == 'Add'){
            $data = new ProjectType([
                'type' => $request->type
            ]);
            $data->save();
            return response()->json(['message'=>'Successfully Add a new type.!','status'=>97],200);
        }elseif($request->action == 'Update'){
            $arr = array('type'=>$request->type);
            DB::table('project_types')
                ->where('id', $request->id)
                ->update($arr);
            return response()->json(['message'=>'Successfully Updated.!','status'=>97],200);
        
        }
    }



    //For get all rolles in the database only for edit role
    public function getAllRoles(){
        $sql = "SELECT name,id FROM roles";
        $allRoles = DB::select(DB::raw($sql));
        return $allRoles;
    }

    //for display user details if the search email is already exists in database
    public function showUserDetail(Request $request){
        $user = User::select('*')->where('email',$request->email)->get();
        return $user;
    }

    //for update user position
    public function updateRole(Request $request){
        $sql = "SELECT name FROM roles WHERE id =".($request->role_id);
        $data = DB::select(DB::raw($sql));
       // return response()->json(['data' => $data[0]->name], 200);
        $update_details1 = array(
            'role_id' => (int)($request->role_id),
        );
        $update_details2 = array(
            'position' => $data[0]->name,
        );
        DB::table('user_role')
            ->where('user_id', $request->user_id)
            ->update($update_details1);
        DB::table('users')
        ->where('id', $request->user_id)
        ->update($update_details2);
        return response()->json(['message' => 'Successfully updated!'], 200);
    }


    //for deactivate user account by admin
    public function deactivateAccount(Request $request){
        $update_details = array(
            'active' => (0),
        );
        DB::table('users')
        ->where('email', $request->email)
        ->update($update_details);

        return response()->json(['message' => 'Successfully deactivated account!'], 200);
    }

    //for activate user account by admin
    public function activateAccount(Request $request){
        $update_details = array(
            'active' => (1),
        );
        DB::table('users')
        ->where('email', $request->email)
        ->update($update_details);

        return response()->json(['message' => 'Successfully activated account!'], 200);
    }


    //for deactivate user account by user
    public function confirmDeactivateAccount(){
        $update_details = array(
            'active' => (0),
        );
        DB::table('users')
        ->where('id', Auth::user()->id)
        ->update($update_details);

        return response()->json(['message' => 'Successfully deactivated account!'], 200);
    }
    
}
