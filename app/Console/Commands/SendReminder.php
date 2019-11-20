<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Scholarship;
use App\ReportUploadModel;
use App\User;
use App\ReportUploadRem;


use App\Notifications\ReportUploadReminder;
use Notification;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        //get today
        $dt = Carbon::now();
        $tArray = explode('-', $dt->format('Y-m-d'));
        $tArray = array_map('intval', $tArray);
        //$now_month = $tArray[1];

        $student = Scholarship::all();

        foreach($student as $v){
            
            $repot = (ReportUploadModel::where('student_id',$v->user_id)->latest()->get())[0];
            $type = (User::select('registrationType')->where('id',$v->user_id)->get())[0]->registrationType;
            // echo $repot."- ";
             echo $type."\n";
            $ls = explode('-', $repot->created_at->format('Y-m-d'));
            $ls = array_map('intval', $tArray);

            $user = User::find($v->user_id); 

            if($type == "schoolStudent"){

                if($tArray[1] == 11 && $repot->term !=3 ){//10 venuwata 1 danna

                    $text = "Your ".($tArray[0]-1).", 3rd term test report not yet uploaded.";
                    Notification::send($user, new ReportUploadReminder($text));

                    $student = new ReportUploadRem([
                        'student_id' => $user->id,
                        'grade' => ($tArray[0]-1),
                        'term' => 3
                    ]);

                }else if($tArray[1] == 5 && $repot->term !=1){

                    $text = "Your ".$tArray[0].", 1st term test report not yet uploaded.";
                    Notification::send($user, new ReportUploadReminder($text));

                    $student = new ReportUploadRem([
                        'student_id' => $user->id,
                        'grade' => $tArray[0],
                        'term' => 1
                    ]);

                }else if($tArray[1] == 9 && $repot->term !=2){

                    $text = "Your ".$tArray[0].", 2nd term test report not yet uploaded.";
                    Notification::send($user, new ReportUploadReminder($text));

                    $student = new ReportUploadRem([
                        'student_id' => $user->id,
                        'grade' => $tArray[0],
                        'term' => 2
                    ]);
                }
            }else{
                //echo "sds\n";
                if($repot->created_at <= Carbon::now()->subMonths(4)){
                    if($repot->semester==1){

                        $text = "Your level ".$repot->acadamic_year." semester 2 report not yet uploaded.";

                        $student = new ReportUploadRem([
                            'student_id' => $user->id,
                            'level' => $repot->acadamic_year,
                            'semester' => 2
                        ]);
                        


                    }else{
                        $text = "Your level ".($repot->acadamic_year+1)." semester 1 report not yet uploaded.";

                        $student = new ReportUploadRem([
                            'student_id' => $user->id,
                            'level' => ($repot->acadamic_year+1),
                            'semester' => 1
                        ]);

                    }
                    Notification::send($user, new ReportUploadReminder($text));
                }
            }

            $student->save();
        }   
         
    }
}
