<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    //登陆页
    public function index(){
        return view('session.login');
    }

    //登陆
    public function store(Request $request){
        //表单验证
        $this->validate($request,[
            'account' => 'required|max:100',
            'password' => 'required',
            'verification' => 'required|captcha',
        ]);
        //账号密码验证
        if(Auth::attempt(['account' => $request->account, 'password' => $request->password, ], $request->has('remember'))){
            session()->flash('success', '欢迎回来！');
            return redirect('/');
        }else{
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配！');
            return redirect()->back();
        }
    }
}
