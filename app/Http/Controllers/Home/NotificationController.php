<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    //通知列表
    public function index(){
        if(!Auth::check()){
            return redirect('/login');
        }
        //获取当前用户id
        $userId = Auth::user()->id;
        //查询用户的通知
        $notification = DB::table('notifications')->where('notifiable_id',$userId)->paginate(6);
        //实例化Carbon
        $dt = Carbon::now();
        foreach ($notification as $value){
            $value->data = json_decode($value->data);
            //将时间格式化为Carbon
            $time = carbon::parse($value->created_at);
            $value->created_at = $time->diffForHumans($dt);
        }
        return view('notification.list',compact('notification'));
    }
}
