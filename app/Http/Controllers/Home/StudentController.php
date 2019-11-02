<?php

namespace App\Http\Controllers\Home;

use App\Models\Dormitory;
use App\Models\Dormitory_member;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profession_code;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Building;
use App\Events\BatchEntry;
use Importer;
use Exporter;

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
        //找到学号、姓名、性别、邮箱、手机在哪列
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
        $student = [];
        $i = 0;
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
            $student[$i]['the_student_id']= $value[$student_id_num];
            $student[$i]['name']= $value[$name_num];
            $student[$i]['sex']= $value[$sex_num];
            ++$i;
        }
        if(empty($student)){
            //读取文件失败，报错
            session()->flash('danger',"文件为空，或者文件内容不规范！请按照格式填写。");
            return redirect()->back();
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
        //完善学生信息
        foreach($student as $key => $value) {
            //遍历专业代码，找出学生对应专业与学院
            $code = substr($value['the_student_id'], 2, 5);
            $class = substr($value['the_student_id'], 7, 1);
            foreach ($colleges as $info) {
                if ($code == $info['code']) {
                    $student[$key]['profession'] = $info['profession'];
                    $student[$key]['college'] = $info['college'];
                    $student[$key]['class'] = $class;
                    break;
                }
            }
            //如果找不到，报错
            if (empty($student[$key]['profession']) || empty($student[$key]['college'])) {
                //插入失败，报错
                session()->flash('danger', "姓名为：" . $value['name'] . ",学号为：" . $value['the_student_id'] . "的学生匹配学院出错！请检查该学生的学号是否正确");
                return redirect()->back();
            }
        }
        //分块执行
        $student = collect($student);
        $info = $student->chunk(100);
        foreach ($info as $key => $value){
            //触发事件
            event(new BatchEntry($value));
        }

        session()->flash('success','成功提交录入，后台将会在5分钟内录入完毕！');
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
            'the_student_id' => 'required',
            'verification' => 'required|captcha',
        ]);
        //分隔学号
        $studentId = str_replace('；',';',$request->the_student_id);
        $studentId = explode(';',$studentId);
        //查询学生信息
        $student = Student::whereIn('the_student_id',$studentId)->where('is_arrange',1)->get();
        if($student->count() <= 0){
            session()->flash('danger','查询的学生不存在！请检查输入是否有误');
            return redirect()->back();
        }
        //查询住宿信息与手机 邮箱信息
        $i = 0;
        foreach ($student as $value){
            $dormitory[$i] = $value->dormitory_member->dormitory;
            $building[$i] = $dormitory[$i]->building;
            ++$i;
        }
        return view('student.show',compact('student','dormitory','building'));
    }

    //学生-信息导出
    public function export(Request $request){
        if($request->has('single')){
            //查询学生信息
            $info = Student::whereIn('id',$request->single)->select('the_student_id','name','sex','profession','class','college','email','phone')->orderBy('the_student_id','asc')->get();
            //获取查询学生的学号
            foreach ($info as $key => $value){
                $num[$key] = $value->the_student_id;
            }
            //初始化Excel文件的第一行
            $information[0] = ['学号','姓名','性别','学院','专业','班级','邮箱','手机号码'];
            //添加到information里
            foreach ($info as $key => $value){
                $information[$key+1][0] = $value->the_student_id;
                $information[$key+1][1] = $value->name;
                $information[$key+1][2] = $value->sex;
                $information[$key+1][3] = $value->college;
                $information[$key+1][4] = $value->profession;
                $information[$key+1][5] = $value->class.'班';
                $information[$key+1][6] = $value->email;
                $information[$key+1][7] = $value->phone;
            }
            //转化类型
            $info = collect($information);
            //使用插件导出Excel文件
            $excel = Exporter::make('Excel');
            $excel->load($info);
            $name = 'storage/student/'.date('Y-m-d-h-i-s').rand(0,9).'.xlsx';
            $excel->save($name);
            return response()->download($name,'学生信息表.xlsx');
        }else{
            session()->flash('danger','您还没有选择要导出的对象！');
            return redirect()->back();
        }
    }

    //学生-宿舍学生信息查询页
    public function dormitorySearch(){
        return view('student.dormitorySearch');
    }

    //学生-宿舍学生信息查询逻辑
    public function dormitoryShow(Request $request){
        //验证表单
        $this->validate($request,[
            'area' => 'required',
            'building' => 'required',
            'dormitory' => 'required',
            'verification' => 'required|captcha',
        ]);
        //分隔宿舍号，第一步先将中文的分号替换成英语的标点
        $dormitoryId = str_replace('；',';',$request->dormitory);
        $dormitoryId = explode(";",$dormitoryId);
        if(empty($dormitoryId)){
            //输入有误 报错
            session()->flash('danger','宿舍填写有误，请检查你的填写！');
            return redirect()->back();
        }
        //查询大楼id
        $buildingId = Building::where('area',$request->area)->where('building',$request->building)->select('id')->first();
        if(empty($buildingId)){
            //如果没查询到大楼，报错
            session()->flash('danger','大楼不存在，请检查你的填写是否有误！');
            return redirect()->back();
        }
        //将对象转化为一维数组
        $buildingId = collect($buildingId)->flatten();
        //查询宿舍信息
        $dormitory = Dormitory::where('building_id',$buildingId)->whereIn('house_num',$dormitoryId)->select('id')->get();
        if($dormitory->count()  <= 0){
            //如果没查询到宿舍，报错
            session()->flash('danger','宿舍不存在或已关闭，请检查你的填写是否有误！');
            return redirect()->back();
        }
        //临时变量
        $studentId = [];
        //查询学生id
        foreach ($dormitory as $key => $value){
            $s = Dormitory_member::where('dormitory_id',$value->id)->select('student_id')->get();
            if($s->count() <= 0){
                continue;
            }
            //循环获取学生id
            foreach ($s as $k => $value){
                $studentId[$k] = $value->student_id;
            }
            if(empty($studentId)){
                continue;
            }
            //查询学生信息
            $student[$key]= Student::whereIn('id',$studentId)->select('id','the_student_id','name','sex','profession','college','class','email','phone')->get();
        }
        if(empty($student)){
            //查询的宿舍无人入住
            session()->flash('danger','查询的宿舍暂无人员入住！');
            return redirect()->back();
        }
        //其他信息（大楼，区域信息）
        $building = $request->building;
        $area = $request->area;
        return view('student.dormitoryShow',compact('student','dormitoryId','building','area'));
    }

    //学生-大楼学生信息查询页
    public function buildingSearch(){
        return view('student.buildingSearch');
    }

    //学生-大楼信息查询逻辑
    public function buildingShow(Request $request){
        //授权
        $this->authorize('general',Student::class);
        //表单验证
        $this->validate($request,[
            'area' => 'required',
            'building' => 'required',
            //'verification' => 'required|captcha',
        ]);
        //查找大楼
        $building = Building::where('area',$request->area)->where('building',$request->building)->select('id')->first();
        if(empty($building)){
            //未找到大楼
            session()->flash('danger','未找到对应大楼，请检查您的输入！');
            return redirect()->back();
        }
        //查询宿舍id
        $dormitory = Dormitory::where('building_id',$building->id)->get();
        if($dormitory->count() <= 0){
            //未找到宿舍id
            session()->flash('danger','出现错误，可能是该大楼未完成初始化，请检查！');
            return redirect()->back();
        }
        //将对象转为一维数组
        $dormitoryId = [];
        foreach ($dormitory as $key => $value){
            $dormitoryId[$key] = $value->id;
        }
        //查询学生id
        $studentId = Dormitory_member::whereIn('dormitory_id',$dormitoryId)->get();
        if($studentId->count() <= 0){
            //未找到学生id
            session()->flash('danger','该大楼暂无人入住，请检查输入！');
            return redirect()->back();
        }
        //将对象转为一维数组
        $id = [];
        foreach ($studentId as $key => $value){
            $id[$key] = $value->student_id;
        }
        //查询学生信息
        $student = Student::whereIn('id',$id)->get();
        //添加宿舍信息
        foreach ($student as $s){
            foreach ($studentId as $roon){
                if($roon->student_id == $s->id){
                    $d_id = $roon->dormitory_id;
                    break;
                }
            }
            foreach ($dormitory as $d){
                if($d->id == $d_id){
                    $s['house_num'] = $d->house_num;
                    break;
                }
            }
        }
        //其他信息
        $area = $request->area;
        $building = $request->building;
        return view('student.buildingShow',compact('student','area','building'));
    }
}
