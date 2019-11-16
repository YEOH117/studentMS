<?php

namespace App\Http\Controllers\Home;

use App\Events\SendNotice;
use App\Models\Building;
use App\Models\Dormitory;
use App\Models\Dormitory_member;
use App\Models\Notification;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SendNoticeController extends Controller
{
    //管理员发送通知页
    public function index(){
        //授权
        $this->authorize('send',Notification::class);
        return view('admin.notice.index');
    }

    //发送通知逻辑
    public function store(Request $request){
        //授权
        $this->authorize('send',Notification::class);
        //表单验证
        $this->validate($request,[
            'people' => 'required',
            'notification' => 'required',
            'verification' => 'required|captcha',
        ]);
        switch ($request->people){
            case 2:
            case '2':
                //二次表单验证
                $this->validate($request,[
                    'area' => 'required',
                    'building' => 'required',
                ]);
                //查询大楼
                break;
            case 3:
            case '3':
                //二次表单验证
                $this->validate($request,[
                    'studentId' => 'required',
                ]);
                break;
        }
        //如果选择全部
        if($request->people == 1){
            //获取用户
            $user = User::where('grade',1)->get();
            //循环触发通知事件
            foreach ($user as $item) {
                //触发事件
                event(new SendNotice($item,$request->notification));
            }
        }
        //如果选择部分人
        if($request->people == 2 && $request->has('area') && $request->has('building')){
            //切割宿舍楼
            $buildings = str_replace('；',';',$request->building);
            $buildings = explode(';',$buildings);
            //查询宿舍楼
            $buildings = Building::where('area',$request->area)->whereIn('building',$buildings)->get();
            if($buildings->count() <= 0){
                session()->flash('danger','查无此宿舍大楼，请检查您的填写！');
                return redirect()->back();
            }
            foreach ($buildings as $building){
                $dormitorysId = Dormitory::where('building_id',$building->id)->select('id')->get();
                //循环获取宿舍id
                foreach ($dormitorysId as $key => $value){
                    $dormitory[$key] = $value->id;
                }
                //查询住宿情况
                $studentId = Dormitory_member::whereIn('dormitory_id',$dormitory)->select('student_id')->get();
                if($studentId->count() <= 0){
                    session()->flash('danger','该宿舍大楼未安排学生，请检查填写！');
                    return redirect()->back();
                }
                //循环获取学生id
                foreach ($studentId as $key => $value){
                    $student[$key] = $value->student_id;
                }
                //查询学生学号
                $student = Student::whereIn('id',$student)->select('the_student_id')->get();
                //循环获取学生学号
                foreach ($student as $key => $value) {
                    $num[$key] = $value->the_student_id;
                }
                //查询学生用户
                $users = User::whereIn('account',$num)->get();
                //循环触发通知事件
                foreach ($users as $item) {
                    //触发事件
                    event(new SendNotice($item,$request->notification));
                }
            }
        }

        //如果选择个别学生
        if($request->people == 3 && $request->has('studentId')){
            //切割学生id
            $studentId = str_replace('；',';',$request->studentId);
            $studentId = explode(';',$studentId);
            //查询学生用户
            $users = User::whereIn('account',$studentId)->get();
            if($users->count() <= 0){
                session()->flash('danger','查无此学生，请检查您的填写！');
                return redirect()->back();
            }
            //循环触发通知事件
            foreach ($users as $item) {
                //触发事件
                event(new SendNotice($item,$request->notification));
            }
        }

        session()->flash('success','通知发送成功！');
        return back();
    }
}
