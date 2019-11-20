<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReportUploadModel;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class ReportUploadController extends Controller
{
    //
    public function saveFile(Request $request){
      //return $request;
        $File = $request -> file('myfile'); //line 1
        $sub_path = 'files/last_result'; //line 2
        $real_name = (now()->format('Y-m-d-H-i-s')).'.jpg'; //line 3
    
        $destination_path = public_path($sub_path);  //line 4
        
        $File->move($destination_path,  $real_name);  //line 5
        
       // return $request;

        $report = new ReportUploadModel([
            'student_id' => Auth::user()->id,
            'acadamic_year' =>(int)($request->acadamic_year),
            'semester' =>(int)($request->semester),
            'school_grade' =>(int)($request->school_grade),
            'term' =>(int)($request->term),
            'path' => $real_name
        ]);

        $report -> save();

        //for delete late uploaded report notification
        DB::table('report_upload_rems')
            ->where('student_id', Auth::user()->id,)
            ->delete();

        return response() -> json([
            'message' => 'Successfully saved file in database'
        ],201);
    
     
  }

  //for get the studenttype(university or school)
  public function getStudentType(){

    $sql = "SELECT registrationType,dob FROM users WHERE id = ".Auth::user()->id;
    $data = DB::select(DB::raw($sql));

    
    if($data[0]->registrationType == "universityStudent"){
     
      //for get last uploded report's cadamic_year and semester
      $sql = "SELECT acadamic_year, semester FROM reportupload WHERE student_id = ".Auth::user()->id .
      " ORDER BY created_at desc" ;
      $lastreport = DB::select(DB::raw($sql));
      
      //calculate the result should return
      if($lastreport[0]->acadamic_year >= 7){
        return response()->json([
          'message' => 'You are not an university student.'
        ], 404);
      }elseif($lastreport[0]->semester == 2){
        $next_semester =1; 
        $next_acadamic_year = $lastreport[0]->acadamic_year + 1;
      }else{
        $next_semester =$lastreport[0]->semester + 1; 
        $next_acadamic_year = $lastreport[0]->acadamic_year;
      }
      
      return response() -> json([
        'acadamic_year' => $next_acadamic_year,
        'semester' => $next_semester,
        'type' => 'universityStudent'
      ],201);
     
    }elseif($data[0]->registrationType == "schoolStudent"){

      //get dob
      $dob = $data[0]->dob;
      $dArray = explode('-', $dob);
      $dArray = array_map('intval', $dArray);
      $month = $dArray[1];

      //get today
      $dt = Carbon::now();
      $tArray = explode('-', $dt->format('Y-m-d'));
      $tArray = array_map('intval', $tArray);
      $tmonth = $tArray[1];

      //calculate age
      $age=Carbon::parse($data[0]->dob)->diff( Carbon::now())->format('%y');
      $intage = (int)$age;

      //calculate grade 
      $sage = $intage - 5;
      if($month == 1){
        $grade = $sage + 1;
      }else{
        $grade = $age;
      }

      //calculate term
      // if($tmonth >= 1 && $tmonth < 4){
      //   $term = 3;
      // }elseif($tmonth >= 4 && $tmonth < 8){
      //   $term = 1;
      // }elseif($tmonth >= 8 && $tmonth < 12){
      //   $term = 2;
      // }

      //for get last uploded report's grade and term
      $sql = "SELECT school_grade,term FROM reportupload WHERE student_id = ".Auth::user()->id .
      " ORDER BY created_at desc" ;
      $lastreport = DB::select(DB::raw($sql));

      //return response()->JSON($lastreport[0]) ;
      

      //calculate the result should return
      if($lastreport[0]->school_grade >= 14){
        return response()->json([
          'message' => 'You are not a school student.'
        ], 404);
      }
    elseif($lastreport[0]->term == 3){
        $next_term =1; 
        $next_grade = $lastreport[0]->school_grade + 1;
      }else{
        $next_term =$lastreport[0]->term + 1; 
        $next_grade = $lastreport[0]->school_grade;
      }

      return response() -> json([
        'grade' => $next_grade,
        'term' => $next_term,
        'type' => 'schoolStudent'
      ],201);
      
      

    }else{
      return null;

    } 
    

  }
}
