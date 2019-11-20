<?php

namespace App\Http\Controllers;

use App\Mentor;
use App\User;
use App\Chat;
use App\Role;
use App\UserRoles;
use App\MentorList;
use App\MentorStudentMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Auth::user();
        $mentor = Auth::user()->mentor();
        
        return $mentor;
    
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mentor = User::find($id);
        return $mentor->name;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }

    public function getChat(Request $request){

        $sql = "SELECT sender_id, receiver_id, message FROM mentor_student_msgs WHERE (sender_id = ".Auth::user()->id." AND receiver_id = ".$request->receiver_id.") or (receiver_id =".Auth::user()->id." AND sender_id = ".$request->receiver_id.") AND status =1";
        
        $chats = DB::select(DB::raw($sql));

        return $chats;
    }

    public function storeChat(Request $request){

        $message = MentorStudentMsg::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'read' => 0,
            'status' => 1
        ]);

        return $message;
    }

    public function confirmStudent(Request $request){

        //DB::update('update mentors set status = 1 where  = ? and mentor_id = ?', [,]);
      
        $users = DB::table('mentors')
                     
                     ->where('student_id', '=', $request->student_id)
                     ->where( 'mentor_id','=', Auth::user()->id)
                     ->update(['status'=> 1]);
          return response()->json(['message'=>'Successfully Accepted','status'=>97]);   
    }

    public function showMentorList(){

        $sql = 'SELECT users.id as mentor_id, users.name, users.subject  FROM users WHERE users.id IN (SELECT user_id FROM user_role WHERE user_role.role_id = 2) AND users.id NOT IN (SELECT mentors.mentor_id FROM mentors WHERE mentors.student_id='.(Auth::user()->id).')';
        
        $users = DB::select(DB::raw($sql));
    
        /*$users = DB::table('users')
                    ->join('user_role', 'users.id', '=', 'user_role.user_id')
                    ->select('users.id','users.name')
                    ->where('user_role.role_id','=',2)
                    ->get();//*/
        return $users;
    }

    public function askFromMentor(Request $request){
        
        Mentor::create([
            'student_id' => Auth::user()->id,
            'mentor_id' => $request->mentor_id,
            'status' => 0
        ]);
        return response()->json(['message'=>'Successfully Requested','status'=>97]);
    }

    public function requestedStudentList(){

        $sql = 'SELECT users.id as student_id, users.name, users.dob  FROM users WHERE users.id IN (SELECT mentors.student_id FROM mentors WHERE mentors.mentor_id='.Auth::user()->id.' AND mentors.status = 0)';
        
        $students = DB::select(DB::raw($sql));
    
        /*$users = DB::table('users')
                    ->join('user_role', 'users.id', '=', 'user_role.user_id')
                    ->select('users.id','users.name')
                    ->where('user_role.role_id','=',2)
                    ->get();//*/
        return $students;
    }

    public function mentorsListForStudent(){
        
        $myMentors = DB::table('users')
                    ->join('mentors', 'users.id', '=', 'mentors.mentor_id')
                    ->select('users.id','users.name','users.subject', 'mentors.status')
                    ->where('mentors.student_id','=',Auth::user()->id)
                    ->get();

        return $myMentors;
    }

    public function studentListForMentors(){
        
        $myStudent = DB::table('users')
                    ->join('mentors', 'users.id', '=', 'mentors.student_id')
                    ->select('users.id','users.name', 'mentors.status')
                    ->where('mentors.mentor_id','=',Auth::user()->id)
                    ->get();

        return $myStudent;
    }
}
