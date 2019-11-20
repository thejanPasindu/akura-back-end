<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class TestImaheupload extends Controller
{
    public function index(Request $request){
       
        return User::find(1)->roles;
    }
}
