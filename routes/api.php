<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    //below for get imgs for scholoarship
    Route::get('imgBirth/{name}', 'ScholarshipConrtoller@imgGetBirth_certificate');
    Route::get('imgConfirm/{name}', 'ScholarshipConrtoller@imgGetConfirm_letter');
    Route::get('imgResult/{name}', 'ScholarshipConrtoller@imgGetResult');
    Route::get('imgGN/{name}', 'ScholarshipConrtoller@imgGetGNConfirm');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
        Route::get('mentor', 'ChatController@index');
        Route::get('getRole', 'AuthController@userRole');
            //Route::get('chat/{id}', 'ChatController@show');

        //for get chat msgs
        Route::post('getchat', 'ChatController@getChat');

        //for sent chat msg
        Route::post('sendchat', 'ChatController@storeChat');
        
        //this for apply scholarship
        Route::post('applyScholarship', 'ScholarshipConrtoller@store');
        Route::post('saveSibling', 'ScholarshipConrtoller@storeSiblings');
        Route::get('studentType', 'ScholarshipConrtoller@index');//used to get student type 
        Route::get('newSchList', 'ScholarshipConrtoller@sendNewSchList'); //use to send new applications
        Route::post('schDetails', 'ScholarshipConrtoller@sendSchDetails'); //use to send sch details
        Route::post('rejectApplication', 'ScholarshipConrtoller@rejectScholarship'); //use to reject application
        Route::get('rejectedApplicationList', 'ScholarshipConrtoller@rejectedScholarships'); //use to reject application
        Route::post('approveApplication', 'ScholarshipConrtoller@approveApplication'); //use to approve sch application 
        Route::get('approvedApplications', 'ScholarshipConrtoller@approvedScholarships'); //use to get list of approved sch applications    
        Route::get('suggesentsList', 'ScholarshipConrtoller@suggesentListForSponsor'); //use to return suggesent sch list for sponsor
        Route::get('isAlredyApplied', 'ScholarshipConrtoller@isAlredyApply');//use for check befor apply or not 
        Route::post('updateSch', 'ScholarshipConrtoller@updateScholarship');//use for update details
        Route::delete('cancelSch', 'ScholarshipConrtoller@cancelScholarship');//use for cancle sch application
        Route::get('schDetailsForSponsor/{id}', 'ScholarshipConrtoller@SchDetailsForSponsor');//this for send sch details for sponsor
        Route::post('sponsorOfferSch', 'ScholarshipConrtoller@offerScholarship');//for offer sch mentor
        Route::get('getExpReport', 'ScholarshipConrtoller@getExpRepor');//for get sch expired report upload list
        Route::any('getStudent/{id}', 'ScholarshipConrtoller@getStudent');//for get student

        //this for Project menagement
        Route::get('projectManager', 'ProjectController@getProjectManager');//for get the project managers list
        Route::get('projectCoordinator', 'ProjectController@getProjectCoordinator'); //for get the project coordinators list 
        Route::get('district', 'ProjectController@getDistrict');//for get the districts list
        Route::get('type', 'ProjectController@getType');//for get the project types list
        Route::post('searchProject', 'ProjectController@searchProject');//for search projects for particular district 
        Route::get('allProject', 'ProjectController@allProject');//for display all project list
        Route::post('enrollWorkshop', 'ProjectController@storeEnroll'); //when click enroll button store details in database
        Route::post('createProject', 'ProjectController@store');
        Route::post('unEnrollWorkshop', 'ProjectController@storeUnenroll');//for unEnrollment

        //this for Setting Page
        Route::get('allType', 'SettingController@getProjectType');//for get a project type list which are in database
        Route::get('allRoles', 'SettingController@getAllRoles');//For get all roles in the database
        Route::get('confirmDeactivate', 'SettingController@confirmDeactivateAccount');//For deactivate user account by user 
        Route::post('detail', 'SettingController@showUserDetail');//For get detail of the particular email
        Route::post('editProject', 'SettingController@editProject');//For edit project type
        Route::post('editRole', 'SettingController@updateRole');//For update user position
        Route::post('deactivate', 'SettingController@deactivateAccount');//For deactivate user account by admin 
        Route::post('activate', 'SettingController@activateAccount');//For activate user account br admin

        Route::get('xxx',[
            'uses' => 'TestImaheupload@index',
            'as' => 'xxx',
            'middleware' => 'roles',
            'roles' => ['admin']
        ]);
        
        //for find and request mentors
            //Route::get('mentorList', 'ChatController@showMentorList');
            //Route::post('mentoringRequest', 'ChatController@askFromMentor');
        Route::get('mentorList',[
            'uses' => 'ChatController@showMentorList',
            'as' => 'mentorList',
            'middleware' => 'roles',
            'roles' => ['admin','student']
        ]);
        Route::post('mentoringRequest',[
            'uses' => 'ChatController@askFromMentor',
            'as' => 'mentoringRequest',
            'middleware' => 'roles',
            'roles' => ['admin','student']
        ]);

        //for accsept student and requested student list
        Route::get('studentList', 'ChatController@requestedStudentList');
        Route::post('confirmStudent', 'ChatController@confirmStudent');

        //for get mentors list which student has
        Route::get('myMentorsList', 'ChatController@mentorsListForStudent');

        //for get students list which mentor has 
        Route::get('myStudentsList', 'ChatController@studentListForMentors');
        
        //for profile management
        Route::get('profileDetais', 'ProfileManagement@getProfil');
        Route::post('profileDetaisSave', 'ProfileManagement@updateData');
        Route::post('profileAvatar', 'ProfileManagement@saveAvatar');
        Route::get('getMyAvatar', 'ProfileManagement@getAvatar');
        Route::get('deleteAvatar', 'ProfileManagement@deleteAvatar');
        Route::post('resetPassword', 'AuthController@resetPass'); //this goes to AuthController

        //for upload progress reports
        Route::post('file', 'ReportUploadController@saveFile');

        //for get student type for calculate next term of report upload
        Route::get('student', 'ReportUploadController@getStudentType');

        /******************************Notification ********************************/
        Route::get('noti', 'CommNotificationController@my');
        Route::get('getNotification', 'CommNotificationController@loadNotification');//this is used for get first 10 notification
        Route::get('markNotification', 'CommNotificationController@markNotification');
    });
});

Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});


Route::post('incomeUpload', 'IncomeController@create');
Route::get('index', 'ProjectController@index');


//Route::post('createProject', 'ProjectController@store');
