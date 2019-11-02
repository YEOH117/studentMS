<?php

namespace App\Http\Controllers\Home;

use App\Models\Dormitory_member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Building;
use App\Models\Dormitory;
use App\Events\CreateDormitoryMember;
use App\Events\UpdateIsArrange;

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
    public function sort(Request $request)
    {
        //授权
        $this->authorize('general', Student::class);
        //根据表单获取对应学生
        if ($request->has('all')) {
            $student = Student::where('is_arrange', 0)->get();
        } else {
            if ($request->has('single')) {
                $studentId = $request->single;
                $student = Student::whereIn('id', $studentId)->where('is_arrange', 0)->get();
            } else {
                session()->flash('danger', '必须选中一位学生，你没有选中任何一个学生！全部智能排宿请使用上面的 全部智能排宿 按钮。');
                return redirect()->back();
            }
        }
        if ($student->count() <= 0) {
            //没有学生信息，报错
            session()->flash('danger', '所有学生都已经安排好宿舍了，不需要安排了！');
            return redirect()->back();
        }
        //对象转数组
        $student = $student->toArray();
        //循环处理学生排宿
        while (count($student)) {
            //获取$student中的第一个学生
            $first = $student[0];
            //切割学号，得到第一个学生的学院代码
            $FirstCollegeId = substr($first['the_student_id'], 2, 5);
            //男学生变量
            $MaleStudent = collect([]);
            //女学生变量
            $FemaleStudent = collect([]);
            //遍历学生，将同一学院学生按男女分别挑出来
            for ($i = 0; $i < count($student); ++$i) {
                //切割学号
                $CollegeId = substr($student[$i]['the_student_id'], 2, 5);
                if ($CollegeId == $FirstCollegeId && $student[$i]['is_arrange'] == 0) {
                    //将同一学院学生按男女分别添加进学生变量
                    switch ($student[$i]['sex']) {
                        case '男':
                            $MaleStudent->push($student[$i]);
                            break;
                        case '女':
                            $FemaleStudent->push($student[$i]);
                            break;
                        default:
                            return;
                    }
                    //删除$student中该元素
                    array_splice($student, $i, 1);
                    $i -= 1;
                }
            }
            //男学生不为空
            if (!empty($MaleStudent)) {
                //查找偏好男宿舍楼
                $building = Building::where('preference', 'like', '%'.$CollegeId.'%')
                    ->where('sex', 0)
                    ->where('empty_num', '>=', $MaleStudent->count() / 8)
                    ->orderBy('empty_num', 'desc')
                    ->first();
                if (empty($building)) {
                    //没找到偏好宿舍,查找剩余宿舍最多的宿舍大楼
                    $building = Building::where('sex', 0)
                        ->where('empty_num', '>=', $MaleStudent->count() / 8)
                        ->orderBy('empty_num', 'desc')
                        ->first();
                }
                if (empty($building)) {
                    //没有找到宿舍大楼,错误，报错
                    session()->flash('danger', '没有一栋男宿舍楼有空位，请检查宿舍楼设置。');
                    return redirect()->back();
                }
                //宿舍楼剩余空宿舍
                $empty_num = $building->empty_num;
                //对象转数组
                $MaleStudent = $MaleStudent->toArray();
                //循环排宿
                while (count($MaleStudent)) {
                    //获取$MaleStudent中的第一个学生
                    $first = $MaleStudent[0];
                    //切割学号，得到第一个学生的偏好代码
                    $FirstCollegeId = substr($first['the_student_id'], 2, 6);
                    //同班级学生变量
                    $SameClassStudents = collect([]);
                    //遍历学生，将同一班级学生挑出来
                    for ($i = 0; $i < count($MaleStudent); ++$i) {
                        //切割学号
                        $ClassId = substr($MaleStudent[$i]['the_student_id'], 2, 6);
                        if ($ClassId == $FirstCollegeId) {
                            //挑学生
                            $SameClassStudents->push($MaleStudent[$i]);
                            //删除$MaleStudent中该元素
                            array_splice($MaleStudent, $i, 1);
                            $i -= 1;
                        }

                    }
                    //获取偏好
                    $CollegeId = substr($SameClassStudents[0]['the_student_id'], 2, 5);
                    $ClassId = substr($SameClassStudents[0]['the_student_id'], 2, 6);
                    //查找偏好宿舍
                    $dormitory = Dormitory::where('building_id', $building->id)
                        ->where('preference', $ClassId)
                        ->where('Remain_number', '>=', 1)
                        ->orderBy('house_num', 'desc')
                        ->first();
                    if (empty($dormitory)) {
                        //没有匹配到偏好宿舍
                        $dormitory = Dormitory::where('building_id', $building->id)
                            ->where('preference', 'like', '%'.$CollegeId.'%')
                            ->where('Remain_number', '>=', 1)
                            ->orderBy('house_num', 'desc')
                            ->first();
                    }
                    if (empty($dormitory)) {
                        //如果是没有匹配到专业宿舍
                        //安排上层宿舍
                        $dormitory = Dormitory::where('building_id', $building->id)
                            ->where('Remain_number', '>=', 4)
                            ->orderBy('house_num', 'desc')
                            ->first();
                    }
                    //获取宿舍剩余空位
                    $RemainNume = $dormitory->Remain_number;
                    //已安排学生id
                    $id = [];
                    $i = 0;
                    //创建宿舍人员记录
                    foreach ($SameClassStudents as $value) {
                        //获取学生id
                        $id[$i] = $value['id'];
                        ++$i;
                        //宿舍空位减一
                        $RemainNume -= 1;
                        if ($RemainNume < 0) {
                            //没有空位
                            //获取宿舍号
                            $HouseNum = $dormitory->house_num;
                            //更新宿舍剩余人数
                            $dormitory->Remain_number = 0;
                            $dormitory->save();
                            //获取下一宿舍
                            $dormitory = Dormitory::where('building_id', $building->id)
                                ->where('house_num', '<', $HouseNum)
                                ->where('Remain_number', '>=', 4)
                                ->orderBy('house_num', 'desc')
                                ->first();
                            if (empty($dormitory)) {
                                session()->flash('danger', '出现未知错误，请稍后再试！');
                                return redirect()->back();
                            }
                            //重新获取宿舍剩余人数，并设置宿舍楼空余宿舍-1
                            $RemainNume = $dormitory->Remain_number - 1;
                            $empty_num -= 1;
                        }
                        //创建宿舍人员记录
                        event(new CreateDormitoryMember($dormitory->id, $value['id']));
                    }
                    //更新学生已安排宿舍信息
                    event(new UpdateIsArrange($id));
                    //块排宿完毕，更新宿舍剩余人数与宿舍偏好
                    $dormitory->preference = $ClassId;
                    $dormitory->Remain_number = $RemainNume;
                    $dormitory->save();
                    //更新宿舍楼剩余空宿舍
                    $building->empty_num = $empty_num;
                    $building->save();

                }
            }
            //女学生不为空
            if (!empty($FemaleStudent)) {
                //查找偏好男宿舍楼
                $building = Building::where('preference', 'like', '%'.$CollegeId.'%')
                    ->where('sex', 1)
                    ->where('empty_num', '>=', $FemaleStudent->count() / 8)
                    ->orderBy('empty_num', 'desc')
                    ->first();
                if (empty($building)) {
                    //没找到偏好宿舍,查找剩余宿舍最多的宿舍大楼
                    $building = Building::where('sex', 1)
                        ->where('empty_num', '>=', $FemaleStudent->count() / 8)
                        ->orderBy('empty_num', 'desc')
                        ->first();
                }
                if (empty($building)) {
                    //没有找到宿舍大楼,错误，报错
                    session()->flash('danger', '没有一栋男宿舍楼有空位，请检查宿舍楼设置。');
                    return redirect()->back();
                }
                //宿舍楼剩余空宿舍
                $empty_num = $building->empty_num;
                //对象转数组
                $FemaleStudent = $FemaleStudent->toArray();
                //循环排宿
                while (count($FemaleStudent)) {
                    //获取$FemaleStudent中的第一个学生
                    $first = $FemaleStudent[0];
                    //切割学号，得到第一个学生的偏好代码
                    $FirstCollegeId = substr($first['the_student_id'], 2, 6);
                    //同班级学生变量
                    $SameClassStudents = collect([]);
                    //遍历学生，将同一班级学生挑出来
                    for ($i = 0; $i < count($FemaleStudent); ++$i) {
                        //切割学号
                        $ClassId = substr($FemaleStudent[$i]['the_student_id'], 2, 6);
                        if ($ClassId == $FirstCollegeId) {
                            //挑学生
                            $SameClassStudents->push($FemaleStudent[$i]);
                            //删除$FemaleStudent中该元素
                            array_splice($FemaleStudent, $i, 1);
                            $i -= 1;
                        }

                    }
                    //获取偏好
                    $CollegeId = substr($SameClassStudents[0]['the_student_id'], 2, 5);
                    $ClassId = substr($SameClassStudents[0]['the_student_id'], 2, 6);
                    //查找偏好宿舍
                    $dormitory = Dormitory::where('building_id', $building->id)
                        ->where('preference', $ClassId)
                        ->where('Remain_number', '>=', 1)
                        ->orderBy('house_num', 'desc')
                        ->first();
                    if (empty($dormitory)) {
                        //没有匹配到偏好宿舍
                        $dormitory = Dormitory::where('building_id', $building->id)
                            ->where('preference', 'like', '%'.$CollegeId.'%')
                            ->where('Remain_number', '>=', 1)
                            ->orderBy('house_num', 'desc')
                            ->first();
                    }
                    if (empty($dormitory)) {
                        //如果是没有匹配到专业宿舍
                        //安排上层宿舍
                        $dormitory = Dormitory::where('building_id', $building->id)
                            ->where('Remain_number', '>=', 4)
                            ->orderBy('house_num', 'desc')
                            ->first();

                    }
                    //获取宿舍剩余空位
                    $RemainNume = $dormitory->Remain_number;
                    //已安排学生id
                    $id = [];
                    $i = 0;
                    //创建宿舍人员记录
                    foreach ($SameClassStudents as $value) {
                        //获取学生id
                        $id[$i] = $value['id'];
                        ++$i;
                        //宿舍空位减一
                        $RemainNume -= 1;
                        if ($RemainNume < 0) {
                            //没有空位
                            //获取宿舍号
                            $HouseNum = $dormitory->house_num;
                            //更新宿舍剩余人数
                            $dormitory->Remain_number = 0;
                            $dormitory->save();
                            //获取下一宿舍
                            $dormitory = Dormitory::where('building_id', $building->id)
                                ->where('house_num', '<', $HouseNum)
                                ->where('Remain_number', '>=', 4)
                                ->orderBy('house_num', 'desc')
                                ->first();
                            if (empty($dormitory)) {
                                session()->flash('danger', '出现未知错误，请稍后再试！' . $HouseNum . '+' . $building->empty_num);
                                return redirect()->back();
                            }
                            //重新获取宿舍剩余人数，并设置宿舍楼空余宿舍-1
                            $RemainNume = $dormitory->Remain_number - 1;
                            $empty_num -= 1;
                        }
                        //创建宿舍人员记录
                        event(new CreateDormitoryMember($dormitory->id, $value['id']));
                    }
                    //更新学生已安排宿舍信息
                    event(new UpdateIsArrange($id));
                    //块排宿完毕，更新宿舍剩余人数与宿舍偏好
                    $dormitory->preference = $ClassId;
                    $dormitory->Remain_number = $RemainNume;
                    $dormitory->save();
                    //更新宿舍楼剩余空宿舍
                    $building->empty_num = $empty_num;
                    $building->save();

                }
            }
        }
        session()->flash('success', '提交智能排宿成功，系统将在后台自动智能排宿，请根据安排人数等待3~10分钟！');
        return redirect('/');

    }

}
