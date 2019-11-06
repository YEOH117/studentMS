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
//        //查询用户住宿信息
//        $info = Student::where('the_student_id',\Auth::user()->account)->first();
//        if(empty($info)){
//            //查不到用户信息
//            session()->flash('danger','发生未知错误，你无法进入该页面！');
//            return redirect('/');
//        }
//        //查询宿舍id
//        $domitoryId = Dormitory_member::where('student_id',$info->id)->first();
//        if(empty($domitoryId)){
//            //用户未安排住宿
//            session()->flash('danger','你尚未被安排住宿，请等待宿舍管理员安排后再访问此功能！');
//            return redirect('/');
//        }
//        //查询宿舍信息
//        $dormitory = Dormitory::where('id',$domitoryId->dormitory_id)->first();
//        if(empty($dormitory)){
//            //宿舍信息不存在
//            session()->flash('danger','发生未知错误！');
//            return redirect('/');
//        }
//        //查询宿舍楼信息
//        $building = Building::where('id',$dormitory->building_id)->first();
//        if(empty($building)){
//            //宿舍楼信息不存在
//            session()->flash('danger','发生未知错误！');
//            return redirect('/');
//        }
//        return view('AdjustApplication.index',compact('info','dormitory','building'));
        return view('AdjustApplication.index');
    }

    //申请调宿逻辑 人-人
    public function store(Student $student){
        //授权
        $this->authorize('adjustApplication',Dormitory_member::class);
        //不能与自身进行调宿
        $user = \Auth::user();
        if($user->account == $student->the_student_id){
            session()->flash('danger','不能与自身进行调宿！');
            return redirect()->back();
        }
        //查询目标学生id与用户id
        $targetId = User::where('account',$student->the_student_id)->first();
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
            'info' => '等待对方同意',
            'state' => 1,
            'token' => $token,
        ]);
        //返回
        session()->flash('success','操作成功，等待对方响应。');
        return redirect('/');
    }

    //申请调宿逻辑 人-空位
    public function noneStore(Dormitory $dormitory){
        //授权
        $this->authorize('adjustApplication',Dormitory_member::class);
        //用户
        $user = Auth::user();
        //用户学生信息
        $userInfo = Student::where('the_student_id',$user->account)->first();
        //用户住宿情况
        $userDormitoryId = Dormitory_member::where('student_id',$userInfo->id)->first();
        //用户宿舍信息
        $userDormitory = Dormitory::find($userDormitoryId->dormitory_id);
        //用户宿舍楼信息
        $userBuilding = Building::find($userDormitory->building_id);
        if($userBuilding->area){
            $userArea = '西';
        }else{
            $userArea = '东';
        }
        //查询目标宿舍的宿舍楼信息
        $building = Building::find($dormitory->building_id);
        if($building->area){
            $area = '西';
        }else{
            $area = '东';
        }
        //查找宿舍管理员账号
        $admin = User::where('grade',2)->get();
        //生成token
        $token = str_random(60);
        //创建调宿记录
        $moveStudent = Movestudent::create([
            'target_id' => $dormitory->id,
            'user_id' => $user->id,
            'info' => '对方同学已同意，等待管理员处理。',
            'state' => 2,
            'token' => $token,
        ]);
        $title = '宿舍调换申请';
        $info = '';
        $info .= $user->name.'（所在宿舍：<code>'.$userArea.$userBuilding->building.'栋'.$userDormitory->house_num.'宿舍'.'</code>）';
        $info .= '申请调换进'.'<code>'.$area.$building->building.'栋'.$dormitory->house_num.'宿舍，'.'</code>';
        $info .= '是否同意？点击下面链接进行操作：<a href="/dormitory/adjust/'.$user->id.'/';
        $info .= $token.'/none'.'">'.route('adjust_show_none',[$user->id,$token]).'</a>';
        //调用 发送管理员事件
        event(new NotificationToAdmins($title,$info,$admin));
        //返回
        session()->flash('success','操作成功，等待宿舍管理员处理。');
        return redirect('/');
    }

    //对方应答申请 页
    public function response(User $user,$token){
        //寻找申请记录
        $moveStudent = Movestudent::where('user_id',$user->id)->first();
        //如果已经处理了，不能再进入
        if($moveStudent->state == 2){
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
        if($movestudent->state == 2){
            session()->flash('danger','你已经处理了，无需再次处理！');
            return redirect('/');
        }
        //进行操作
        if($judge){
            //用户同意对方申请
            //修改记录的状态
            $movestudent->state = 2;
            $movestudent->info = '对方同学已同意，等待管理员处理。';
            $movestudent->save();
            //查找宿舍管理员账号
            $admin = User::where('grade',2)->get();
            //信息
            $title = '宿舍调换申请';
                //查找用户与目标用户住宿信息
            $targetId = User::where('id',$movestudent->target_id)->first();
            $userId = User::where('id',$movestudent->user_id)->first();
            $user = $userId;
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
            $info .= '进行宿舍调换，是否同意？点击下面链接进行操作：<a href="/dormitory/adjust/'.$movestudent->user_id.'/';
            $info .= $token.'">'.route('adjust_show',[$movestudent->user_id,$token]).'</a>';
            //调用 发送管理员事件
            event(new NotificationToAdmins($title,$info,$admin));
            //发送给申请者通知
            $title = '你的调宿申请有进展了';
            $info = Auth::user()->name.'同意了你的调宿申请，等待宿舍管理员审核。';
            event(new NotificationToStudents($user,$title,$info));
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

    //宿舍申请列表
    public function list(){
        //授权
        $this->authorize('look',Movestudent::class);
        //查看申请表中，进度为管理员处理的记录
        $moveStudent = Movestudent::where('state',2)->get();
        foreach ($moveStudent as $key => $value){
            $targetId[$key] = $value->target_id;
            $user_id[$key] = $value->user_id;
        }
        if($moveStudent->count()){
            $target = User::whereIn('id',$targetId)->get();
            $user = User::whereIn('id',$user_id)->get();
            foreach ($moveStudent as $value){
                foreach($target as $t){
                    if($value->target_id == $t->id){
                        $value['targetName'] = $t->name;
                    }
                }
                foreach($user as $t){
                    if($value->user_id == $t->id){
                        $value['userName'] = $t->name;
                    }
                }
            }
        }else{
            $moveStudent= [];
        }

        return view('admin.adjust.application.list',compact('moveStudent'));
    }

    //宿舍申请 人-人 处理页
    public function show($userId,$token){
        //授权
        $this->authorize('look',Movestudent::class);
        //查询申请记录
        $moveStudent = Movestudent::where('user_id',$userId)->first();
        if(empty($moveStudent)){
            session()->flash('danger','来晚了，申请已经被其他人处理了！');
            return redirect(route('adjust_list'));
        }
        //token对比
        if($token != $moveStudent->token){
            session()->flash('danger','请求错误！');
            return redirect('/');
        }
        //查询申请人与被申请人的用户信息
        $user = User::find($userId);
        $target = User::find($moveStudent->target_id);
        if(empty($user) || empty($target)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生信息
        $user = Student::where('the_student_id',$user->account)->first();
        $target = Student::where('the_student_id',$target->account)->first();
        if(empty($user) || empty($target)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生住宿
        $userDormitoryId = Dormitory_member::where('student_id',$user->id)->first();
        $targetDormitoryId = Dormitory_member::where('student_id',$target->id)->first();
        if(empty($userDormitoryId) || empty($targetDormitoryId)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生宿舍id
        $userDormitory = Dormitory::where('id',$userDormitoryId->dormitory_id)->first();
        $targetDormitory = Dormitory::where('id',$targetDormitoryId->dormitory_id)->first();
        if(empty($userDormitory) || empty($targetDormitory)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生宿舍楼id
        $userBuilding = Building::where('id',$userDormitory->building_id)->first();
        $targetBuilding = Building::where('id',$targetDormitory->building_id)->first();
        if(empty($userBuilding) || empty($targetBuilding)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //组合信息
        $userInfo['name'] = $user->name;
        $userInfo['studentId'] = $user->the_student_id;
        $userInfo['sex'] = $user->sex;
        $userInfo['profession'] = $user->profession;
        $userInfo['college'] = $user->college;
        $userInfo['email'] = $user->email;
        $userInfo['phone'] = $user->phone;
        $userInfo['class'] = $user->class;
        $userInfo['houseNum'] = $userDormitory->house_num;
        $userInfo['area'] = $userBuilding->area;
        $userInfo['building'] = $userBuilding->building;
        //目标用户信息
        $targetInfo['name'] = $target->name;
        $targetInfo['studentId'] = $target->the_student_id;
        $targetInfo['sex'] = $target->sex;
        $targetInfo['profession'] = $target->profession;
        $targetInfo['college'] = $target->college;
        $targetInfo['email'] = $target->email;
        $targetInfo['phone'] = $target->phone;
        $targetInfo['class'] = $target->class;
        $targetInfo['houseNum'] = $targetDormitory->house_num;
        $targetInfo['area'] = $targetBuilding->area;
        $targetInfo['building'] = $targetBuilding->building;
        return view('admin.adjust.application.show',compact('userInfo','targetInfo','moveStudent','token'));
    }

    //宿舍申请 人-空位 处理页
    public function noneShow($userId,$token){
        //授权
        $this->authorize('look',Movestudent::class);
        //查询申请记录
        $moveStudent = Movestudent::where('user_id',$userId)->first();
        if(empty($moveStudent)){
            session()->flash('danger','来晚了，申请已经被其他人处理了！');
            return redirect(route('adjust_list'));
        }
        //token对比
        if($token != $moveStudent->token){
            session()->flash('danger','请求错误！');
            return redirect('/');
        }
        //查询申请人与被申请人的用户信息
        $user = User::find($userId);
        if(empty($user)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生信息
        $user = Student::where('the_student_id',$user->account)->first();
        if(empty($user) ){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生住宿
        $userDormitoryId = Dormitory_member::where('student_id',$user->id)->first();
        if(empty($userDormitoryId)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生宿舍id
        $userDormitory = Dormitory::where('id',$userDormitoryId->dormitory_id)->first();
        if(empty($userDormitory)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //查询学生宿舍楼id
        $userBuilding = Building::where('id',$userDormitory->building_id)->first();
        if(empty($userBuilding)){
            session()->flash('danger','发生未知错误，请稍后再试！');
            return redirect('/');
        }
        //组合信息
        $userInfo['name'] = $user->name;
        $userInfo['studentId'] = $user->the_student_id;
        $userInfo['sex'] = $user->sex;
        $userInfo['profession'] = $user->profession;
        $userInfo['college'] = $user->college;
        $userInfo['email'] = $user->email;
        $userInfo['phone'] = $user->phone;
        $userInfo['class'] = $user->class;
        $userInfo['houseNum'] = $userDormitory->house_num;
        $userInfo['area'] = $userBuilding->area;
        $userInfo['building'] = $userBuilding->building;
        //查询要调换宿舍信息
        $target = Dormitory::find($moveStudent->target_id);
        $building = Building::find($target->building_id);
        return view('admin.adjust.application.noneShow',compact('userInfo','target','building','moveStudent','token'));
    }

    //管理员处理申请 人-人
    public function process(Movestudent $movestudent,$token,$judge){
        //授权
        $this->authorize('look',$movestudent);
        //查看token是否一致
        if($token != $movestudent->token){
            session()->flash('danger','你没有权限做此操作！');
            return redirect('/');
        }
        //获取学生
        $user = User::find($movestudent->user_id);
        $target = User::find($movestudent->target_id);
        if($judge){
            //进行宿舍调整
            $userStudent = Student::where('the_student_id',$user->account)->first();
            $targetStudent = Student::where('the_student_id',$target->account)->first();
            if(empty($userStudent) || empty($targetStudent)){
                session()->flash('danger','出现未知错误！请稍后再试。');
                return redirect('/');
            }
            //查找住宿情况
            $userDormitory = Dormitory_member::where('student_id',$userStudent->id)->first();
            $targetDormitory = Dormitory_member::where('student_id',$targetStudent->id)->first();
            if(empty($userStudent) || empty($targetStudent)){
                session()->flash('danger','出现未知错误！请稍后再试。');
                return redirect('/');
            }
            //进行调整
            $userDormitory->student_id = $targetStudent->id;
            $userDormitory->save();
            $targetDormitory->student_id = $userStudent->id;
            $targetDormitory->save();
            //通知信息
            $title = '调宿申请结果通知';
            $content = '你发出的 与'.$target->name.'调换宿舍 的申请已经被宿舍管理员通过，即日进行宿舍调整！';
            $content2 = '你与'.$user->name.'调换宿舍 的申请已经被宿舍管理员通过，即日进行宿舍调整！';
            //触发事件 通知学生
            event(new NotificationToStudents($user,$title,$content));
            event(new NotificationToStudents($target,$title,$content2));
            //删除调宿申请记录
            $movestudent->delete();
        }else{
            //通知信息
            $title = '调宿申请结果通知';
            $content = '你发出的 与'.$target->name.'调换宿舍 的申请,被宿舍管理员拒绝！';
            $content2 = '你与'.$user->name.'调换宿舍 的申请被宿舍管理员拒绝！！';
            //触发事件 通知学生
            event(new NotificationToStudents($user,$title,$content));
            event(new NotificationToStudents($target,$title,$content2));
            //删除调宿申请记录
            $movestudent->delete();
        }
        session()->flash('success','操作成功！');
        return redirect(route('adjust_list'));
    }

    //管理员处理申请 人-空位
    public function noneProcess(Movestudent $movestudent,$token,$judge){
        //授权
        $this->authorize('look',$movestudent);
        //查看token是否一致
        if($token != $movestudent->token){
            session()->flash('danger','你没有权限做此操作！');
            return redirect('/');
        }
        //获取学生
        $user = User::find($movestudent->user_id);
        //获取宿舍号
        $dormitory = Dormitory::find($movestudent->target_id);
        if($judge){
            //进行宿舍调整
            $userStudent = Student::where('the_student_id',$user->account)->first();
            if(empty($userStudent)){
                session()->flash('danger','出现未知错误！请稍后再试。');
                return redirect('/');
            }
            //查找住宿情况
            $userDormitory = Dormitory_member::where('student_id',$userStudent->id)->first();
            if(empty($userStudent)){
                session()->flash('danger','出现未知错误！请稍后再试。');
                return redirect('/');
            }
            //查找宿舍信息
            $d = Dormitory::find($userDormitory->dormitory_id);
            $dor = Dormitory::find($movestudent->target_id);
            //进行调整
            $dor->Remain_number -= 1;
            $dor->save();
            $d->Remain_number += 1;
            $d->save();
            $userDormitory->dormitory_id = $movestudent->target_id;
            $userDormitory->save();
            //通知信息
            $title = '调宿申请结果通知';
            $content = '你发出的 调换至'.$dormitory->house_num.'宿舍 的申请已经被宿舍管理员通过，即日进行宿舍调整！';
            //触发事件 通知学生
            event(new NotificationToStudents($user,$title,$content));
            //删除调宿申请记录
            $movestudent->delete();
        }else{
            //通知信息
            $title = '调宿申请结果通知';
            $content = '你发出的 调换至'.$dormitory->house_num.'宿舍 的申请,被宿舍管理员拒绝！';
            //触发事件 通知学生
            event(new NotificationToStudents($user,$title,$content));
            //删除调宿申请记录
            $movestudent->delete();
        }
        session()->flash('success','操作成功！');
        return redirect(route('adjust_list'));
    }

    //学生 我的申请列表页
    public function myList(){
        //授权
        $this->authorize('manage',Movestudent::class);
        //获取当前用户
        $user = Auth::user();
        //查询我的申请
        $myApplication = Movestudent::where('user_id',$user->id)->first();
        //查询关于我的申请
        $otherApplication = Movestudent::where('target_id',$user->id)->where('state',1)->get();
        //组合信息
        if(empty($myApplication) == false){
            $t = User::find($myApplication->target_id);
            $t = Student::where('the_student_id',$t->account)->first();
            $myInfo['target'] = $t;
            $myInfo['user'] = $user->name;
            $myInfo['token'] = $myApplication->token;
            $myInfo['state'] = $myApplication->info;
            $myInfo['movestudent'] = $myApplication->id;
        }
        if($otherApplication->count() > 0){
            foreach ($otherApplication as $key => $value){
                $u = User::find($value->user_id);
                $u = Student::where('the_student_id',$u->account)->first();
                $otherInfo[$key]['target'] = $u;
                $otherInfo[$key]['user'] = $user->name;
                $otherInfo[$key]['token'] = $value->token;
                $otherInfo[$key]['user_id'] = $value->user_id;
                $otherInfo[$key]['state'] = '等待你的同意';
            }
        }
        return view('AdjustApplication.myList',compact('myInfo','otherInfo'));
    }

    //删除我的调宿请求
    public function delete(Movestudent $movestudent){
        //授权
        $this->authorize('owner',$movestudent);
        //删除记录
        $movestudent->delete();
        session()->flash('success','删除调宿请求成功！');
        return redirect('/');
    }
}
