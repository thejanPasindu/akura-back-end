<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Role;
use App\UserRoles;
use App\Notifications\SignupActivate;
use DB;
use Hash;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {

       //return response($request->role);
        //return $request;
        $role = $request->role;
        $request->validate([
            'sname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required|string'
        ]);
        $user = new User([
            'name' => $request->sname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => str_random(60),
            'fname' => $request->fname,
            'mname' => $request->mname,
            'cnumber' => $request->cnumber,
            'no' => $request->no,
            'street' => $request->street,
            'city' => $request->city,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'position' => $request->position,
            'occupation' => $request->occupation,
            'registrationType' => $request->registrationType,
            'studingYear' => $request->studingYear,
            'studingSemester' => $request->studingSemester,
            'paymentType' => $request->paymentType,
            'paymentAmount' => $request->paymentAmount,
            'medium' => $request->medium,
            'subject' => $request->subject,//*/
        ]);

        $user->save();
        $user->notify(new SignupActivate($user));

        $user_id = $user->id;
        $role_id = Role::where('name',$request->role)->first()->id;

        $user_role = new UserRoles([
            'user_id'=>$user_id,
            'role_id'=>$role_id
        ]);

        $user_role->save();

        return response()->json([
            'message' => 'Successfully created user!',
            'role' => $role_id
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $sql = "SELECT roles.name FROM roles WHERE roles.id = (SELECT user_role.role_id FROM user_role WHERE user_role.user_id = ".Auth::user()->id.")";
        $role = DB::select(DB::raw($sql));

        return response()->json(['user'=>$request->user(),'role'=>$role]);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return $user;
    }

    public function userRole(){
        $sql = "SELECT roles.name FROM roles WHERE roles.id = (SELECT user_role.role_id FROM user_role WHERE user_role.user_id = ".Auth::user()->id.")";
        $role = DB::select(DB::raw($sql));
        return $role;
    }


    //for reset Password
    public function resetPass(Request $request){

        $password = $request->input('old_password');
        
        $request->validate([
            'password' => 'required|string|confirmed',
        ]);

        if(Hash::check($password, Auth::user()->password)){
            $update_details = array(
                'password' => bcrypt($request->password)
            );
            
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update($update_details);

            return response()->json(['message'=>'Successfully Reset'], 200);
        }
            
        return response()->json(['message'=>'Unauthorized'], 401);
    }
}
