<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    //登陆页
    public function index(){
        return view('session.login');
    }

    //登陆
    public function store(){

    }
}
