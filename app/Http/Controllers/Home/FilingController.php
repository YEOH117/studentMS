<?php

namespace App\Http\Controllers\Home;

use App\Models\Filing;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilingController extends Controller
{
    //查看登记备案列表
    public function index(){
        //授权
        $this->authorize('general',Student::class);
        $filing = Filing::with('student')->orderBy('created_at','desc')->paginate(20);
        return view('admin.filing.list',compact('filing'));
    }

    //ajax查询 某位学生 备案记录
    public function ajaxInquire($student){
        $student = Student::where('the_student_id',$student)->first();
        $info = '';
        $filing = Filing::where('student_id',$student->id)->orderBy('created_at','desc')->get();
        if($filing->count() <= 0){
            $info = '查询不到该同学记录！';
            $info = json_encode($info);
            return $info;
        }
        foreach ($filing as $value){
            $info .= '<tr>';
            $info .= '<td class="col-md-3">'.$student->name.'</td>';
            if($value->sort){
                $info .= '<td class="col-md-3">住宿登记记录</td>';
            }else{
                $info .= '<td class="col-md-3">离校登记记录</td>';
            }
            $info .= '<td class="col-md-3">'.$value->created_at.'</td>';
            $info .= '<td class="col-md-3"><a href="/filing/'.$value->id.'" class="btn btn-info">查看</a></td>';
            $info .= '</tr>';
        }
        $info = json_encode($info);
        return $info;
    }

    //离校填写表页
    public function leaveSchool(){
        //授权
        $this->authorize('general',Student::class);
        return view('admin.filing.leave');
    }

    //住宿填写表页
    public function deferSchool(){
        //授权
        $this->authorize('general',Student::class);
        return view('admin.filing.defer');
    }

    //备案提交逻辑
    public function store(Request $request){
        //授权
        $this->authorize('general',Student::class);
        //表单验证
        $this->validate($request,[
            'sort' => 'required',
            'student' => 'required|min:10|max:10',
            'info' => 'required',
            'verification' => 'required|captcha',
        ]);
        //查询学生信息
        $student = Student::where('the_student_id',$request->student)->first();
        if(empty($student)){
            session()->flash('danger','查询不到该学生，请检查您的填写！');
            return redirect()->back();
        }
        //创建记录
        $filing = Filing::create([
           'student_id' => $student->id,
           'sort' => $request->sort,
           'info' => $request->info,
        ]);
        if(empty($filing)){
            session()->flash('danger','创建登记记录时出现未知错误，请稍后再试！');
            return redirect()->back();
        }
        session()->flash('success','登记成功！');
        return redirect()->back();
    }

    //显示备案记录
    public function show(Filing $filing){
        //授权
        $this->authorize('general',Student::class);
        return view('admin.filing.show',compact('filing'));
    }
}
