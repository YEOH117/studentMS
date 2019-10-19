<?php

namespace App\Http\Controllers\Home;

use App\Models\Dormitory_member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Building;
use App\Models\Dormitory;

class DormitoryController extends Controller
{
    //新手列表页
    public function NewStudent(){
        //授权
        $this->authorize('general',Student::class);
        $this->authorize('general',Dormitory_member::class);

        $student = Student::where('is_arrange',0)->paginate(10);
        return view('admin.dormitory.student.list',compact('student'));
    }

    //智能排宿
    public function sort(Request $request){
        //授权
        $this->authorize('general',Student::class);
        //根据表单获取对应学生
        if($request->has('all')){
            $student = Student::where('is_arrange',0)->get();
        }else{
            if($request->has('single')){
                $studentId = $request->single;
                $student = Student::whereIn('id',$studentId)->where('is_arrange',0)->get();
            }else{
                session()->flash('danger','必须选中一位学生，你没有选中任何一个学生！全部智能排宿请使用上面的 全部智能排宿 按钮。');
                return redirect()->back();
            }

        }
        //排宿
        foreach ($student as $value){
            //防止注入
            if($value->is_arrange > 0){
                continue;
            }
            //切割学生学号，匹配偏好大楼
            $theStudentId = substr($value->the_student_id,2,5);
            //换算性别
            switch ($value->sex){
                case '男':
                    $sex = 0;
                    break;
                case '女':
                    $sex = 1;
                    break;
                default:
                    session()->flash('danger','出现未知错误，请稍后再试！');
                    return redirect('/');
            }
            $building = Building::where('preference','like',$theStudentId)
                                ->where('sex',$sex)
                                ->where('empty_num','>=',1)
                                ->orderBy('empty_num','desc')
                                ->first();
            $dormitory = null;
            if(empty($building)){
                //匹配不到偏好大楼，匹配空余宿舍最多的大楼
                $building = Building::where('sex',$sex)->where('empty_num','>=',1)->orderBy('empty_num','desc')->first();
                //匹配不到大楼信息，报错
                if(empty($building)){
                    session()->flash('danger','宿舍大楼信息出错，请检查宿舍大楼设置！');
                    return redirect('/');
                }
            }else{
                //切割学生 专业班级
                $professionAndClassCode = substr($value->the_student_id,2,6);
                //匹配偏好宿舍 通过专业班级匹配
                $dormitory = Dormitory::where('building_id',$building->id)
                    ->where('preference',$professionAndClassCode)
                    ->where('Remain_number','>=',1)
                    ->orderBy('house_num','asc ')
                    ->first();
                //匹配不到偏好宿舍,匹配 上个宿舍偏好 字段
                if(empty($dormitory)){
                    $dormitory = Dormitory::where('building_id',$building->id)
                        ->where('last_preference',$professionAndClassCode)
                        ->where('Remain_number','>=',1)
                        ->orderBy('house_num','asc ')
                        ->first();
                }
                //匹配不到 上个宿舍偏好 ，更换 通过专业匹配偏好宿舍
                if(empty($dormitory)){
                    $dormitory = Dormitory::where('building_id',$building->id)
                        ->where('preference','like','%'.$theStudentId)
                        ->where('Remain_number','>=',1)
                        ->orderBy('house_num','asc ')
                        ->first();
                }
                //匹配不到偏好宿舍 ，匹配 上个宿舍偏好 字段
                if(empty($dormitory)){
                    $dormitory = Dormitory::where('building_id',$building->id)
                        ->where('last_preference','like','%'.$theStudentId)
                        ->where('Remain_number','>=',1)
                        ->orderBy('house_num','asc ')
                        ->first();
                }
            }
            //匹配不到专业相关的宿舍 ，随机安排一个上层的宿舍
            if(empty($dormitory)){
                $dormitory = Dormitory::where('building_id',$building->id)
                    ->where('Remain_number','>=',1)
                    ->orderBy('house_num','desc ')
                    ->first();
            }
            //匹配不到宿舍 ，出错了，报错
            if(empty($dormitory)){
                //拼接宿舍大楼名称
                switch($building->area){
                    case 0:
                    case '0':
                        $area = '东';
                        break;
                    case 1:
                    case '1':
                        $area = '西';
                        break;
                    default:
                        $area = '';
                }
                session()->flash('danger','安排宿舍出现问题，请检查宿舍大楼：'.$area.$building->building.'栋的宿舍详情！（可能是为初始化）');
                return redirect('/');
            }
            //匹配到宿舍
           //创建宿舍成员记录
            $dormitory_member = Dormitory_member::create([
               'dormitory_id' => $dormitory->id,
                'student_id' => $value->id,
            ]);
            if(empty($dormitory_member)){
                //出错，报错
                session()->flash('danger','出现未知错误，请稍后再试！');
                return redirect('/');
            }
            //对宿舍信息进行修改
            $d = Dormitory::find($dormitory->id);
            $b = Building::find($building->id);
                //修改 剩余人数 字段
            $dormitory->Remain_number = $dormitory->Remain_number - 1;
            //对大楼信息进行修改
            if($dormitory->Remain_number >= $dormitory->roon_people - 1){
                //如果宿舍剩余人数为5的话，视为新创建的宿舍
                $building->empty_num = $building->empty_num - 1;
                $building->save();
                //修改 宿舍偏好 字段
                $dormitory->preference = $professionAndClassCode;
            }
            //修改 上一个宿舍偏好 字段
            $endNum = substr($dormitory->house_num,1,2);
            if($endNum == $building->layer_roon_num){
                $house_num = substr($dormitory->house_num,0,1) + 1;
                $house_num = $house_num.'01';
                $last = Dormitory::where('house_num',$house_num)->select('preference')->first();
            }else{
                $last = Dormitory::where('house_num',$dormitory->house_num + 1)->select('preference')->first();
            }
            if(empty($last)){
                $dormitory->last_preference = ' ';
            }else{
                $dormitory->last_preference = $last->preference;
            }
            $dormitory->save();
            //标记学生为已安排状态
            $value->is_arrange = 1;
            $value->save();
        }
        session()->flash('success','智能排宿成功！');
        return redirect('/');
    }

}
