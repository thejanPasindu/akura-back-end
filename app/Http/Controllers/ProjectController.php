<?php

namespace App\Http\Controllers;

use App\Project;
use App\Projectaddresss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\EnrollDetail;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Project::find(3)->ProjectAddresss;
        return Projectaddresss::find(5)->project;
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
    public function store(Request $request){
        $project = new Project([
            
           'creater_id' => Auth::user()->id, 
           'project_name' => $request->project_name,
           'project_manager_id' => $request->project_manager_id,
           'project_coordinator1' => $request->project_coordinator1,
           'project_coordinator2' => $request->project_coordinator2,
           'start_date' => $request->start_date,
           'end_date' => $request->end_date,
           'location' => $request->location,
           'district' => $request->district, 
            'city' => $request->city, 
            'zip' => $request->zip, 
            'addres_line_1' => $request->addres_line_1, 
            'addres_line_2' => $request->addres_line_2, 
            'type' => $request->project_type,
           'description' => $request->description
        ]);

        $project->save();

         return response()->json([
             'message' => 'Successfully create project.!',
             'status' => 201
         ], 201);
    }


    //for get project manager dateils
    public function getProjectManager(){
        $sql = "SELECT name,id FROM users WHERE id IN ( SELECT user_id FROM user_role WHERE role_id = 1 )";
        $projectManager = DB::select(DB::raw($sql));

        return $projectManager;
    } 

    //for get project coordinator dateils
    public function getProjectCoordinator(){
        $sql = "SELECT name,id FROM users WHERE id IN ( SELECT user_id FROM user_role WHERE role_id = 3 )";
        $projectCoordinator = DB::select(DB::raw($sql));

        return $projectCoordinator;
    } 


    //for get district list for the drop down list
    public function getDistrict(){
        $sql = "SELECT id,district FROM districts ORDER BY district asc";
        $district = DB::select(DB::raw($sql));

        return $district;
    }

    //for get project type list for the drop down list
    public function getType(){
        $sql = "SELECT type FROM project_types";
        $type = DB::select(DB::raw($sql));

        return $type;
    }


    //for search projects related to particular district
    public function searchProject(Request $request){

        //return response()->json(['k'=>$request->date]);

        if(($request->district)>=0 && $request->date == null){
            $sql = "SELECT id,project_name,start_date,end_date,location,type,district,city,description FROM projects WHERE (type = 'workshop' OR type ='seminar') AND district=".$request->district;
            $data = DB::select(DB::raw($sql));
        }elseif(($request->district)<0 && $request->date != null){
            $sql = "SELECT id,project_name,start_date,end_date,location,type,district,city,description FROM projects WHERE (type = 'workshop' OR type ='seminar') AND start_date ="."'".$request->date."'";
            $data = DB::select(DB::raw($sql));
            //$request->date = $date;
            //return  $data;
        }else{
            $sql = "SELECT id,project_name,start_date,end_date,location,type,district,city,description FROM projects WHERE (type = 'workshop' OR type ='seminar') AND start_date ="."'".$request->date."'"." AND district=".$request->district;
            $data = DB::select(DB::raw($sql));
        }
        return $data;
    }




    //for display all upcoming projects in enroll section
        public function allProject(){
            // $sql = "SELECT id,project_name,start_date,end_date,location,type,district,city,description  FROM projects WHERE type = 'workshop' OR type ='seminar'";
            // $allprojects = DB::select(DB::raw($sql));

            // //get today
            // $dt = Carbon::now();
            // $tArray = explode('-', $dt->format('Y-m-d'));
            // $tArray = array_map('intval', $tArray);

            // //get start date
            // $startDate = $allprojects[0]->start_date;
            // $dArray = explode('-', $startDate);
            // $d2Array = array_map('intval', $dArray);

            // if($tArray[0] >= $d2Array[0] && $tArray[1] >= $d2Array[1] ){
            //     return $allprojects;
            // }
            //return $allprojects;

            

            $now = date('Y-m-d');

            $alredyEnroll = EnrollDetail::select('project_id')
                            ->where('user_id',Auth::user()->id)
                            ->get();

            $ids = array();
            foreach ($alredyEnroll as $value){ 
                array_push($ids,$value->project_id);
            }
            

            $allprojects = Project::select('id', 'project_name', 'start_date', 'end_date', 'location', 'district', 'city', 'description')
                            ->where('start_date', '>=', $now)
                            ->whereNotIn('id', $ids)
                            ->get();

            
            return $allprojects;
        }

         
    //for return already enrolled projects by user
    public function alreadyEnrolled(){
        $sql = "SELECT id,project_name,start_date,end_date,location,type,district,city,description FROM projects WHERE id IN (SELECT project_id FROM enroll_details WHERE state=1 AND user_id=".Auth::user()->id.")";
        $state2 = DB::select(DB::raw($sql)); 
        return $state2;
    }

    //For store user details which are enrolled to workshops and seminars
    public function storeEnroll(Request $request){
        //return $request;
        $enroll = new EnrollDetail([
            'user_id' => Auth::user()->id,
            'project_id' =>(int)($request->id),
        ]);

        $enroll -> save();

        return response() -> json([
            'message' => 'Successfully saved enroll in database'
        ],201);
    }


    //For store user details which are enrolled to workshops and seminars
    public function storeUnenroll(Request $request){

        //return $request;
        DB::table('enroll_details')
            ->where('user_id', '=',Auth::user()->id)
            ->where('project_id', '=',$request->id)
            ->delete();

        return response() -> json([
            'message' => 'Successfully unenrolled'
        ],201);
    }
 


}
