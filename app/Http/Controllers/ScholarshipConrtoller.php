<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

//notifications
use App\Notifications\CallAdmin;
use App\Notifications\CommNotification;
use Notification;


use App\StudentSibling;
use App\StudentScholarshipTemp;
use App\Scholarship;
use App\ReportUploadModel;
use App\StudentScholarship;
use App\User;
use App\ReportUploadRem;

use DB;

class ScholarshipConrtoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sql = "SELECT registrationType FROM users WHERE id = ".Auth::user()->id;
        $data = DB::select(DB::raw($sql));
        return response()->json(['type'=>$data[0]->registrationType]);
        
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
        
        //this for confirm_letter save
        $File = $request -> file('confirm_letter_file'); //line 1
        $sub_path = 'files/confirm_letter'; //line 2
        $confirm_letter_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'.jpg'; //line 3
        $destination_path = public_path($sub_path);  //line 4
        $File->move($destination_path,  $confirm_letter_name);  //line 5

        //this for gn_confirm save
        $File = $request -> file('gn_certificate_file'); //line 1
        $sub_path = 'files/gn_certificate'; //line 2
        $gn_certificate_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'.jpg'; //line 3
        $destination_path = public_path($sub_path);  //line 4
        $File->move($destination_path,  $gn_certificate_name);  //line 5

        //this for birth_certificate save
        $File = $request -> file('birth_certificate_1_file'); //line 1
        $sub_path = 'files/birth_certificate'; //line 2
        $birth_certificate_1_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'_side-1'.'.jpg'; //line 3
        $destination_path = public_path($sub_path);  //line 4
        $File->move($destination_path,  $birth_certificate_1_name);  //line 5

        $File = $request -> file('birth_certificate_2_file'); //line 1
        $sub_path = 'files/birth_certificate'; //line 2
        $birth_certificate_2_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'_side-2'.'.jpg'; //line 3
        $destination_path = public_path($sub_path);  //line 4
        $File->move($destination_path,  $birth_certificate_2_name);  //line 5

        //this for last_result save
        $File = $request -> file('last_result_file'); //line 1
        $sub_path = 'files/last_result'; //line 2
        $last_result_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'.jpg'; //line 3
        $destination_path = public_path($sub_path);  //line 4
        $File->move($destination_path,  $last_result_name);  //line 5

        
        $request->validate([
            'guardian_name' => 'required|string',
            'relationship' => 'required|string|',
            'guardian_address' => 'required|string',
            'family_anual_income' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'family_anual_expence' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'guardian_tp' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);//*/

        //return $request;
        $scholarship = new Scholarship([
            'user_id' => Auth::user()->id,
            'school' => $request->school,
            'university' => $request->university,
            'faculty' => $request->faculty,
            'grade' => (int)($request->grade),
            'academicyear' => (int)($request->academicyear),
            'semester' => (int)($request->semester),
            'addrboarding' => $request->addrboarding,
            'guardian_name' => $request->guardian_name,
            'relationship' => $request->relationship,
            'guardian_tp' => $request->guardian_tp,
            'guardian_address' => $request->guardian_address,
            'family_anual_income' => floatval($request->family_anual_income),
            'family_anual_expence' =>floatval( $request->family_anual_expence),
            'birth_certificate_1' => $birth_certificate_1_name,
            'birth_certificate_2' => $birth_certificate_2_name,
            'confirm_letter' => $confirm_letter_name,
            'gn_certificate' => $gn_certificate_name,
            'approve' => 0,
            'reject' => 0,
            'has_sponsor' => 0,
        ]);

        $report = new ReportUploadModel([
            'student_id' => Auth::user()->id,
            'acadamic_year' =>(int)($request->l_year),
            'semester' =>(int)($request->l_semester),
            'school_grade' =>(int)($request->l_grade),
            'term' =>(int)($request->l_term),
            'path' => $last_result_name
        ]);

        $report -> save();
        $scholarship->save();
        
        $sql = "SELECT user_id FROM user_role WHERE role_id=1";
        $ids = DB::select(DB::raw($sql));
        
        $a=array();
        foreach ($ids as $value) {
            array_push($a,$value->user_id);
        }
        
        $admins = User::whereIn('id',$a)->get();
        if(Auth::user()->registrationType == 'schoolStudent'){
            $student = "School student who is ".Auth::user()->fname." ".Auth::user()->mname." ".Auth::user()->name." is Applied Scholarship.";
        }else{
            $student = "University student who is ".Auth::user()->fname." ".Auth::user()->mname." ".Auth::user()->name." is Applied Scholarship.";
        }
        

        Notification::send($admins, new CallAdmin($student));
        //Notification::send($admins, new CommNotification($student));
        
        return response()->json([
            'message' => 'Successfully Apply!',
            'code' => 100
        ], 201);
    }

    public function storeSiblings(Request $request){

        
        for($i=0;$i<($request->size);$i++){
            
            $sibling = new StudentSibling([
                'user_id' => Auth::user()->id,
                'sibling_name' => $request->siblings[$i]['sibling_name'],
                'sibling_relationship' => $request->siblings[$i]['sibling_relationship'], 
                'school_university' => $request->siblings[$i]['school_university'],
                'grade_year' => $request->siblings[$i]['grade_year']
            ]);

            $sibling->save();
        }

        return response()->json([
            'message' => 'Successfully Apply!',
            'code' => 50
        ], 201);
    }

    //for send scholarship list
    public function sendNewSchList(){
        $sql = "SELECT id, name, registrationType FROM users WHERE id IN (SELECT user_id FROM scholarships WHERE approve=0 and reject=0)  ORDER BY created_at desc";
        $data = DB::select(DB::raw($sql));
        return  $data;
    }

    public function sendSchDetails(Request $request){
        return $this->SchDetails($request->id);
    }
    
    public function SchDetails($id){
        //return $request->id;
        $schDetails = DB::table('scholarships')
                    ->select('id', 'user_id', 'school', 'university', 'faculty', 'grade', 'academicyear', 'semester', 'addrboarding', 'guardian_name', 'relationship', 'guardian_tp', 'guardian_address', 'family_anual_income', 'family_anual_expence','confirm_letter', 'gn_certificate', 'birth_certificate_1', 'birth_certificate_2', 'has_sponsor')
                    ->where('user_id','=',$id)
                    ->get();
        
        $applierDetails = DB::table('users')
                        ->select('fname', 'mname', 'name', 'no', 'street', 'city', 'dob', 'cnumber')
                        ->where('id', '=', $schDetails[0]->user_id)
                        ->get();
        
        $siblingsDetails = DB::table('student_siblings')
                    ->select('id', 'sibling_name', 'sibling_relationship', 'school_university', 'grade_year')
                    ->where('user_id','=',$id)
                    ->get();

        $sql = "SELECT id, acadamic_year, semester, school_grade, term, path AS last_result FROM reportupload WHERE student_id = ".$id." ORDER BY created_at desc";
        $result = DB::select(DB::raw($sql));
 

        return response()->json(['details'=>$schDetails, 'siblings'=>$siblingsDetails, 'result'=>$result[0], 'applierDetails'=>$applierDetails]);
    }


    //this 4 functions for return images
    public function imgGetBirth_certificate($name){
        $path = public_path().'/files/birth_certificate/'.$name;
       // return response()->json(['img'=>Response::download($path), 'code'=>1]);
       return response()->download($path);
    }

    public function imgGetConfirm_letter($name){
        $path = public_path().'/files/confirm_letter/'.$name;
       // return response()->json(['img'=>Response::download($path), 'code'=>1]);
       return response()->download($path);
    }

    public function imgGetResult($name){
        $path = public_path().'/files/last_result/'.$name;
       // return response()->json(['img'=>Response::download($path), 'code'=>1]);
       return response()->download($path);
    }

    public function imgGetGNConfirm($name){
        $path = public_path().'/files/gn_certificate/'.$name;
       // return response()->json(['img'=>Response::download($path), 'code'=>1]);
       return response()->download($path);
    }


    //this fuction for reject sch
    public function rejectScholarship(Request $request){
        $sch = DB::table('scholarships')             
                ->where('user_id', '=', $request->user_id)
                ->update(['reject'=> 1]);
        return response()->json(['message'=>'Successfully Rejected','status'=>97]);  
    }

    //this for get rejected applicasions
    public function rejectedScholarships(){
        $sql = "SELECT id, name, registrationType FROM users WHERE id IN (SELECT user_id FROM scholarships WHERE approve=0 and reject=1)  ORDER BY created_at desc";
        $data = DB::select(DB::raw($sql));
        return  $data;
    }


    //**this for approve application */
    public function approveApplication(Request $request){

        $sql = "SELECT id FROM users WHERE id IN (SELECT user_id FROM user_role WHERE role_id=5) AND id NOT IN (SELECT sponsor_id FROM student_scholarships) AND paymentAmount>=".$request->payment;
        $data = DB::select(DB::raw($sql));

        foreach ($data as $value){
            $tepmSponsor = new StudentScholarshipTemp([
                'student_id' => $request->user_id,
                'sponsor_id' => $value->id,
                'amount' => $request->payment
            ]);
            $tepmSponsor->save();
        }
   
        $data = array(
            'approve'=> 1,
            'reject'=> 0
        );
        $sch = DB::table('scholarships')             
                ->where('user_id', '=', $request->user_id)
                ->update($data);
        return response()->json(['message'=>'Successfully Approve','status'=>97]);

    }

    //this for get approved applicasions
    public function approvedScholarships(){
        $sql = "SELECT id, name, registrationType FROM users WHERE id IN (SELECT user_id FROM scholarships WHERE approve=1 and reject=0)  ORDER BY created_at desc";
        $data = DB::select(DB::raw($sql));


        return  $data;
    }

    //this for get suggesents for sponsor
    public function suggesentListForSponsor(){
        $sql = "SELECT id, name, registrationType FROM users WHERE id IN (SELECT student_id FROM student_scholarship_temps WHERE sponsor_id=".Auth::user()->id.")  ORDER BY created_at desc";
        $data = DB::select(DB::raw($sql));
        return  $data;
    }

    public function isAlredyApply(){
        try{
            return $this->SchDetails(Auth::user()->id);
        }catch(Exception $e){
            return response()->json(['message'=>'not fund'], 404);;
        }
    }

    public function updateScholarship(Request $request){
        //return $request->confirm_letter;
        $update_details = array(
            'school' => $request->school,
            'university' => $request->university,
            'faculty' => $request->faculty,
            'grade' => (int)($request->grade),
            'academicyear' => (int)($request->academicyear),
            'semester' => (int)($request->semester),
            'addrboarding' => $request->addrboarding,
            'guardian_name' => $request->guardian_name,
            'relationship' => $request->relationship,
            'guardian_tp' => $request->guardian_tp,
            'guardian_address' => $request->guardian_address,
            'family_anual_income' => floatval($request->family_anual_income),
            'family_anual_expence' =>floatval( $request->family_anual_expence),
            'birth_certificate_1' => $request->birth_certificate_1,
            'birth_certificate_2' => $request->birth_certificate_2,
            'confirm_letter' => $request->confirm_letter,
            'gn_certificate' => $request->gn_certificate,
            'approve' => 0,
            'reject' => 0, 
            'has_sponsor' => 0,  
        );

        if($request->hasFile('confirm_letter_file')){
            //this for confirm_letter save
            $File = $request -> file('confirm_letter_file'); //line 1
            $sub_path1 = 'files/confirm_letter'; //line 2
            $confirm_letter_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'.jpg'; //line 3
            $destination_path = public_path($sub_path1);  //line 4
            $File->move($destination_path,  $confirm_letter_name);  //line 5
            $update_details=array('confirm_letter' => $confirm_letter_name);
        }

        if($request->hasFile('birth_certificate_1_file')){
            //this for birth_certificate save
            $File = $request -> file('birth_certificate_1_file'); //line 1
            $sub_path = 'files/birth_certificate'; //line 2
            $birth_certificate_1_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'_side-1'.'.jpg'; //line 3
            $destination_path = public_path($sub_path);  //line 4
            $File->move($destination_path,  $birth_certificate_1_name);  //line 5
            $update_details=array('birth_certificate_1' => $birth_certificate_1_name);
        }

        if($request->hasFile('birth_certificate_2_file')){
            //this for birth_certificate save
            $File = $request -> file('birth_certificate_2_file'); //line 1
            $sub_path = 'files/birth_certificate'; //line 2
            $birth_certificate_2_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'_side-2'.'.jpg'; //line 3
            $destination_path = public_path($sub_path);  //line 4
            $File->move($destination_path,  $birth_certificate_2_name);  //line 5
            $update_details=array('birth_certificate_2' => $birth_certificate_2_name);
        }

        if($request->hasFile('gn_certificate_file')){
           //this for gn_confirm save
            $File = $request -> file('gn_certificate_file'); //line 1
            $sub_path = 'files/gn_certificate'; //line 2
            $gn_certificate_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'.jpg'; //line 3
            $destination_path = public_path($sub_path);  //line 4
            $File->move($destination_path,  $gn_certificate_name);  //line 5
            $update_details=array('gn_certificate' => $gn_certificate_name);
        }


        $update_result = array(
            'acadamic_year' =>(int)($request->l_year),
            'semester' =>(int)($request->l_semester),
            'school_grade' =>(int)($request->l_grade),
            'term' =>(int)($request->l_term),
            'path' => $request->last_result
        );

        if($request->hasFile('last_result_file')){
            //this for last_result save
            $File = $request -> file('last_result_file'); //line 1
            $sub_path = 'files/last_result'; //line 2
            $last_result_name = (now()->format('Y-m-d-H-i-s_')).Auth::user()->id.'.jpg'; //line 3
            $destination_path = public_path($sub_path);  //line 4
            $File->move($destination_path,  $last_result_name);  //line 5
            $update_result=array('path' => $last_result_name);
         }

        DB::table('reportupload')
            ->where('student_id', Auth::user()->id,)
            ->update($update_result);

        DB::table('scholarships')
            ->where('user_id', Auth::user()->id,)
            ->update($update_details);

        return response()->json(['message'=>'Successfully Change..!','status'=>97],200);
    }

    //use for delete scholarship
    public function cancelScholarship(){
        DB::delete('delete from reportupload where student_id = ?',[Auth::user()->id]);
        DB::delete('delete from scholarships where user_id = ?',[Auth::user()->id]);
        DB::delete('delete from student_siblings where user_id = ?',[Auth::user()->id]);
        return response()->json(['message'=>'Successfully cancled..!','status'=>97],200);
    }


    //this for return scholarship details for sponsor
    public function SchDetailsForSponsor($id){
       
        $schDetails = DB::table('scholarships')
                    ->select('id', 'user_id', 'school', 'university', 'faculty', 'grade', 'academicyear', 'semester', 'guardian_name', 'relationship', 'guardian_address', 'family_anual_income', 'family_anual_expence','confirm_letter')
                    ->where('user_id','=',$id)
                    ->get();
        
        $applierDetails = DB::table('users')
                        ->select('fname', 'mname', 'name', 'no', 'street', 'city', 'dob')
                        ->where('id', '=', $schDetails[0]->user_id)
                        ->get();
        
        $siblingsDetails = DB::table('student_siblings')
                    ->select('id', 'sibling_name', 'sibling_relationship', 'school_university', 'grade_year')
                    ->where('user_id','=',$id)
                    ->get();

        $sql = "SELECT id, acadamic_year, semester, school_grade, term, path AS last_result FROM reportupload WHERE student_id = ".$id." ORDER BY created_at desc";
        $result = DB::select(DB::raw($sql));

        $suggest_payment = DB::table('student_scholarship_temps')
                            ->select('amount')
                            ->where('student_id',$id)
                            ->where('sponsor_id',Auth::user()->id)
                            ->first();

        return response()->json(['details'=>$schDetails, 'siblings'=>$siblingsDetails, 'result'=>$result[0], 'applierDetails'=>$applierDetails, 'suggest_payment'=>$suggest_payment]);
    }

    //this methord for sponsor
    //to offer scholarship
    public function offerScholarship(Request $request){

        $suggest_payment = DB::table('student_scholarship_temps')
                            ->select('amount')
                            ->where('student_id',$request->user_id)
                            ->where('sponsor_id',Auth::user()->id)
                            ->first();
       
        $scholarship = new StudentScholarship([
            'student_id'=>$request->user_id,
            'sponsor_id'=>Auth::user()->id,
            'amount'=>$suggest_payment->amount,
            'confirmed'=>1
        ]);

        $scholarship->save();

        DB::table('student_scholarship_temps')
            ->where('student_id',$request->user_id)
            ->orWhere('sponsor_id',Auth::user()->id)
            ->delete();
        DB::table('scholarships')
            ->where('user_id', $request->user_id,)
            ->update(['has_sponsor'=>1]);
        

        return response()->json(['message'=>'Successfully Offerd..!'], 200);
        // DB::delete('delete from reportupload where student_id = ?',[Auth::user()->id]);
    }


    //this for send expired progress report upload
    public function getExpRepor(){
        $list = ReportUploadRem::all();
        return $list;
    }

    public function getStudent($id){
        $student = User::select('fname','registrationType')
                    ->where('id','=',$id)
                    ->first();

        return $student;
    }
}

