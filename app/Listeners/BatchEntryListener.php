<?php

namespace App\Listeners;

use App\Events\BatchEntry;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Profession_code;
use App\Models\Student;
use App\Models\User;

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
        $student = $event->info;
        //创建学生记录
        foreach($student as $value){
            //创建学生记录
            $st = Student::create([
                'the_student_id' => $value['the_student_id'],
                'name' => $value['name'],
                'sex' => $value['sex'],
                'profession' => $value['profession'],
                'college' => $value['college'],
                'class' => $value['class'],
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
    }
}
