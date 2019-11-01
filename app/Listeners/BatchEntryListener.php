<?php

namespace App\Listeners;

use App\Events\BatchEntry;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Profession_code;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class BatchEntryListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BatchEntry  $event
     * @return void
     */
    public function handle(BatchEntry $event)
    {
        //获取数据
        $collection = $event->info;
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
        if(empty($student)){
            //读取文件失败，报错
            session()->flash('danger',"文件为空，或者文件内容不规范！请按照格式填写。");
            return false;
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
                return false;
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
                return false;
            }
        }
        //返回，提示成功
        session()->flash('success', '批量导入成功！');
    }

    public function failed(OrderShipped $event, $exception)
    {
        session()->flash('danger','录入时发生未知错误，请稍后再试！');
        return redirect('/');
    }
}
