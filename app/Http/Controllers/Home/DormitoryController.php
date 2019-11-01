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
        if($student->count() <= 0){
            //没有学生信息，报错
            session()->flash('danger','所有学生都已经安排好宿舍了，不需要安排了！');
            return redirect()->back();
        }
        dump($student);

    }

}
