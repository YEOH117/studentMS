<?php

namespace App\Http\Controllers\Home;

use App\Events\NotificationToAdmins;
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
        $this->authorize('adjustApplication',Dormitory_member::class);
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
        $this->authorize('adjustApplication',Dormitory_member::class);
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

    //对方应答申请 页
    public function response(User $user,$token){
        //寻找申请记录
        $moveStudent = Movestudent::where('user_id',$user->id)->first();
        //如果已经处理了，不能再进入
        if($moveStudent->state == '对方同学已同意，等待管理员处理。'){
            session()->flash('danger','你已经处理了，无需再次处理！');
            return redirect('/');
        }
        //授权
        $this->authorize('general',$moveStudent);
        //验证token
        if($moveStudent->token != $token){
            session()->flash('danger','你无权进行此操作！');
            return redirect('/');
        }
        //附带信息
        $info = $user->name.'向你发出 调换宿舍 申请，是否同意？';
        return view('AdjustApplication.respone',compact('info','moveStudent','token'));
    }

    //处理应答
    public function answer(Movestudent $movestudent,$token,$judge){
        //授权
        $this->authorize('general',$movestudent);
        //查看token是否一致
        if($movestudent->token != $token){
            session()->flash('danger','你没有权限做此操作！');
            return redirect('/');
        }
        //如果已经处理了，不能再进入
        if($movestudent->state == '对方同学已同意，等待管理员处理。'){
            session()->flash('danger','你已经处理了，无需再次处理！');
            return redirect('/');
        }
        //进行操作
        if($judge){
            //用户同意对方申请
            //修改记录的状态
            $movestudent->state = '对方同学已同意，等待管理员处理。';
            $movestudent->save();
            //查找宿舍管理员账号
            $admin = User::where('grade',2)->get();
            //信息
            $title = '宿舍调换申请';
                //查找用户与目标用户住宿信息
            $targetId = User::where('id',$movestudent->target_id)->first();
            $userId = User::where('id',$movestudent->user_id)->first();
            if(empty($targetId) || empty($userId)){
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
                //查找用户与目标用户的学生id
            $targetId = Student::where('the_student_id',$targetId->account)->first();
            $userId = Student::where('the_student_id',$userId->account)->first();
            if(empty($targetId) || empty($userId)){
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
                //查找宿舍id
            $targetDormitoryId = Dormitory_member::where('student_id',$targetId->id)->first();
            $userDormitoryId = Dormitory_member::where('student_id',$userId->id)->first();
            if(empty($targetDormitoryId) || empty($userDormitoryId)){
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
                //查找宿舍楼id
            $targetBuildingId = Dormitory::where('id',$targetDormitoryId->dormitory_id)->first();
            $userBuildingId = Dormitory::where('id',$userDormitoryId->dormitory_id)->first();
            if(empty($targetBuildingId) || empty($userBuildingId)){
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
                //查询宿舍楼信息
            $targetBuilding = Building::where('id',$targetBuildingId->building_id)->first();
            $userBuilding = Building::where('id',$userBuildingId->building_id)->first();
            if(empty($targetBuilding) || empty($userBuilding)){
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
            //将信息组合起来
            if ($targetBuilding->area){
                $targetArea = '西';
            }else{
                $targetArea = '东';
            }
            if ($targetBuilding->area){
                $userArea = '西';
            }else{
                $userArea = '东';
            }
            $info = '';
            $info .= $userId->name.'（所在宿舍：<code>'.$userArea.$userBuilding->building.'栋'.$userBuildingId->house_num.'宿舍'.'</code>）';
            $info .= '申请与'.$targetId->name.'（所在宿舍：<code>'.$targetArea.$targetBuilding->building.'栋'.$targetBuildingId->house_num.'宿舍'.'</code>）';
            $info .= '进行宿舍调换，是否同意？';
            //调用 发送管理员事件
            event(new NotificationToAdmins($title,$info,$admin));
        }else{
            $title = '你的调宿申请结果';
            $info = Auth::user()->name.'拒绝了你的调宿申请，调宿失败。';
            //查找申请者
            $user = User::where('id',$movestudent->user_id)->first();
            if(empty($user)){
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
            //触发 通知事件
            event(new NotificationToStudents($user,$title,$info));
            //删除申请记录
            $movestudent->delete();
        }
        //返回
        session()->flash('success','操作成功！');
        return redirect('/');
    }
}
