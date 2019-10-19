<?php

namespace App\Http\Controllers\Home;

use App\Models\Dormitory_member;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profession_code;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Importer;

class StudentController extends Controller
{
    //学生-单个录入-页面
    public function single_entry(){
        //授权
        $this->authorize('add',Student::class);
        return view('admin/student/single_entry');
    }

    //学生-单个录入-逻辑
    public function stroe(Request $request){
        //表单验证
        $this->validate($request,[
            'name' => 'required',
            'the_student_id' => 'digits_between:10,10|required|unique:students',
            'sex' => 'required',
        ]);
        //授权判断
        $this->authorize('add',Student::class);
        $this->authorize('add',User::class);
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
        //同时创建对应账号
        $account = User::create([
            'account' => $request->the_student_id,
            'name' => $request->name,
            'password' => bcrypt($request->the_student_id),
            'grade' => 1,
        ]);
        if(empty($student) || empty($account)){
            //插入失败，报错
            session()->flash('danger','录入时发生未知错误，请稍后再试！');
            return redirect()->back();
        }

        //插入成功,提示成功
        session()->flash('success','录入学生成功！');
        return redirect()->back();
    }

    //学生-批量录入-页面
    public function batch_entry(){
        //授权
        $this->authorize('add',Student::class);
        return view('admin.student.batch_entry');
    }

    //学生-批量录入-逻辑
    public function batch_stroe(Request $request){
        //表单验证
        $this->validate($request,[
            'upfile' => 'required',
        ]);
        //授权判断
        $this->authorize('add',Student::class);
        $this->authorize('add',User::class);
        //定义上传格式
        $extension = array('xlsx','csv');
        if($request->hasFile('upfile')) {
            $file = $request->file('upfile');
            $ext = $file->getClientOriginalExtension();
            if (in_array($ext, $extension)) {
            } else {
                //不符合，报错
                session()->flash('danger', '上传的文件格式错误！请上传文件格式为.xlsx的Excel文件，其他格式文件暂不支持！');
                return redirect()->back();
            }
        }
        //重新命名文件
        $name = date('Y-m-d-h-i-s').rand(0,9).'.'.$ext;
        //保存文件，并获取其路径
        $path = $file->storeAs('public/entry_file', $name);
        $path = str_replace('public','./storage',$path);
        //获取Excel文件内容
        $excel = Importer::make('Excel');
        $excel->load($path);
        $collection = $excel->getCollection();
        //找到学号、姓名、性别在哪列
        foreach($collection[0] as $key => $info){
            if($info == "学号"){
                $student_id_num = $key;
            }
            if($info == "姓名"){
                $name_num = $key;
            }
            if($info == "性别"){
                $sex_num = $key;
            }
        }
        //遍历获取需要的数据
        foreach($collection as $key => $value){
            if($key == 0){
                continue;
            }
            //上传的文件数据可能会出的错误,处理-跳过
            //1.学号为空
            if(empty($value[$student_id_num])){
                continue;
            }
            //2.名字为空
            if(empty($value[$name_num])){
                continue;
            }
            //3.性别为空
            if(empty($value[$sex_num])){
                continue;
            }
            //4.学号长度不为10位
            if(strlen($value[$student_id_num]) != 10){
                continue;
            }
            $student[$key-1]['the_student_id']= $value[$student_id_num];
            $student[$key-1]['name']= $value[$name_num];
            $student[$key-1]['sex']= $value[$sex_num];
        }
        //获取专业代码数据
        $colleges = Profession_code::all();
        //查询所有的student表中的学号
        $student_id = Student::get()->pluck('the_student_id');
        //删除所有重复元素
        foreach ($student_id as $num){
            foreach($student as $key => $value){
                if($value['the_student_id'] == $num){
                    array_splice($student,$key,1);
                }
            }
        }
        //创建学生记录
        foreach($student as $value){
            //遍历专业代码，找出学生对应专业与学院
            $code = substr($value['the_student_id'],2,5);
            $class = substr($value['the_student_id'],7,1);
            foreach($colleges as $info){
                if($code == $info['code']){
                    $profession = $info['profession'];
                    $college = $info['college'];
                    break;
                }
            }
            //如果找不到，报错
            if(empty($profession) || empty($college)){
                //插入失败，报错
                session()->flash('danger',"姓名为：".$value['name']."学号为：".$value['the_student_id']."的学生匹配学院出错！请检查该学生的学号是否正确");
                return redirect()->back();
            }
            //创建学生记录
            $st = Student::create([
                'the_student_id' => $value['the_student_id'],
                'name' => $value['name'],
                'sex' => $value['sex'],
                'profession' => $profession,
                'college' => $college,
                'class' => $class,
                'is_arrage' => 0,
            ]);
            //创建对应的学生账号
            $account = User::create([
                'account' => $value['the_student_id'],
                'name' => $value['name'],
                'password' => bcrypt($value['the_student_id']),
                'grade' => 1,
            ]);
            if(empty($st) || empty($account)){
                //插入失败，报错
                session()->flash('danger','录入时发生未知错误，请稍后再试！');
                return redirect()->back();
            }
        }
        //返回，提示成功
        session()->flash('success', '批量导入成功！');
        return redirect()->back();
    }

    //学生-查询信息页
    public function search(Request $request){
        return view('student.search');
    }

    //学生-查询信息逻辑
    public function show(Request $request){
        //验证表单
        $this->validate($request,[
            'studentId' => 'required',
        ]);
        //分隔学号
        $studentId = explode(';',$request->studentId);
        //查询学生信息
        $student = Student::whereIn('the_student_id',$studentId)->get();
        //查询住宿信息
        $i = 0;
        foreach ($student as $value){
            $dormitory[$i] = $value->dormitory_member->dormitory;
            $building[$i] = $dormitory[$i]->building;
            ++$i;
        }
        return view('student.show',compact('student','dormitory','building'));
    }
}
