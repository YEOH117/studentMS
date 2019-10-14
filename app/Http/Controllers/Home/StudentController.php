<?php

namespace App\Http\Controllers\Home;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profession_code;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentController extends Controller
{
    //学生-单个录入-页面
    public function single_entry(){
        return view('admin/student/single_entry');
    }

    //学生-单个录入-逻辑
    public function stroe(Request $request){
        //表单验证
        $this->validate($request,[
            'name' => 'required',
            'the_student_id' => 'digits_between:10,10|required',
            'sex' => 'required',
        ]);
        //授权判断
        $this->authorize('add',Profession_code::class);
        //获取专业代码
        $professions_code = Profession_code::all();
        if(empty($professions_code)){
            //为空，报错误
            session()->flash('danger', '尚未设置专业代码，请在 首页>专业代码 设置添加专业代码后，再继续此操作！');
            return redirect()->back();
        }
        //通过学号截取专业代码，与数据库对比
        $code = substr($request->the_student_id,2,5);
        foreach($professions_code as $value){
            if($value->code == $code){
                $profession_code = $value;
                break;
            }
        }
        if(empty($profession_code)){
            //数据库无匹配内容，报错
            session()->flash('danger', '无法匹配该学生的专业代码，请检查 专业代码设置 或 学号输入是否正确！');
            return redirect()->back();
        }
        //匹配成功，获取班级，专业，学院信息及将性别换成字符表示
        $profession = $profession_code->profession;
        $college = $profession_code->college;
        $class = substr($request->the_student_id,7,1);
        switch ($request->sex){
            case '1':
                $sex = '男';
                break;
            case '2':
                $sex = '女';
                break;
            default:
                $sex = '不详';
                break;
        }
        //创建学生记录
        $student = Student::create([
           'the_student_id' => $request->the_student_id,
            'name' => $request->name,
            'sex' => $sex,
            'profession' => $profession,
            'college' => $college,
            'class' => $class,
            'is_arrange' => '0',
        ]);
        if(empty($student)){
            //插入失败，报错
            session()->flash('danger','录入时发生未知错误，请稍后再试！');
            return redirect()->back();
        }
        //插入成功,提示成功
        session()->flash('success','录入学生成功！');
        return redirect()->back();
    }
}
