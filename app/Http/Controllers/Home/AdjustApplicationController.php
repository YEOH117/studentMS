<?php

namespace App\Http\Controllers\Home;

use App\Events\NotificationToStudents;
use App\Models\Building;
use App\Models\Dormitory;
use App\Models\Dormitory_member;
use App\Models\Movestudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdjustApplicationController extends Controller
{
    //申请调宿页
    public function index(){
        //授权,只有学生才能访问
        //$this->authorize('adjustApplication',Dormitory_member::class);
        //查询用户住宿信息
        $info = Student::where('the_student_id',\Auth::user()->account)->first();
        if(empty($info)){
            //查不到用户信息
            session()->flash('danger','发生未知错误，你无法进入该页面！');
            return redirect('/');
        }
        //查询宿舍id
        $domitoryId = Dormitory_member::where('student_id',$info->id)->first();
        if(empty($domitoryId)){
            //用户未安排住宿
            session()->flash('danger','你尚未被安排住宿，请等待宿舍管理员安排后再访问此功能！');
            return redirect('/');
        }
        //查询宿舍信息
        $dormitory = Dormitory::where('id',$domitoryId->dormitory_id)->first();
        if(empty($dormitory)){
            //宿舍信息不存在
            session()->flash('danger','发生未知错误！');
            return redirect('/');
        }
        //查询宿舍楼信息
        $building = Building::where('id',$dormitory->building_id)->first();
        if(empty($building)){
            //宿舍楼信息不存在
            session()->flash('danger','发生未知错误！');
            return redirect('/');
        }
        return view('AdjustApplication.index',compact('info','dormitory','building'));
    }

    //申请调宿逻辑
    public function store($studentId){
        //授权
        //$this->authorize('adjustApplication',Dormitory_member::class);
        //不能与自身进行调宿
        $user = \Auth::user();
        if($user->account == $studentId){
            session()->flash('danger','不能与自身进行调宿！');
            return redirect()->back();
        }
        //查询目标学生id与用户id
        $targetId = User::where('account',$studentId)->first();
        $user = Auth::user();
        //生成token
        $token = str_random(60);
        //通知标题
        $title = '调宿请求通知';
        //通知内容，拼接链接
        $content = $user->name.'向你发起互相调换宿舍请求！你可以点击下面链接进行同意或拒绝操作。'
            .'<a href="'.url('/').'/application/'.$user->id.'/'.$token.'">'
            .url('/').'/application/'.$user->id.'/'.$token.'</a>';
        //触发 发送通知给学生 事件
        event(new NotificationToStudents($targetId,$title,$content));
        //添加调宿记录
        $moveStudent = Movestudent::create([
            'target_id' => $targetId->id,
            'user_id' => $user->id,
            'state' => '等待对方同意',
            'token' => $token,
        ]);
        //返回
        session()->flash('success','操作成功，等待对方响应。');
        return redirect('/');
    }
}
