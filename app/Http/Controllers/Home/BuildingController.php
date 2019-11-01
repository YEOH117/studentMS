<?php

namespace App\Http\Controllers\Home;

use App\Models\Building;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dormitory;

class BuildingController extends Controller
{
    //宿舍楼_列表页
    public function list(){
        //授权
        $this->authorize('look',Building::class);
        $area_0 = Building::where('area',0)->orderBy('building','asc')->get();
        $area_1 = Building::where('area',1)->orderBy('building','asc')->get();
        return view('admin.building.list',compact('area_0','area_1'));
    }

    //宿舍楼_创建页
    public function create(){
        //授权
        $this->authorize('create',Building::class);
        //如果有宿舍楼未设置宿舍，跳转至宿舍设置
        $building = Building::where('status',0)->first();
        if(!empty($building)){
            session()->flash('danger','您需完成之前未完成的大楼宿舍初始化操作，然后方可继续创建大楼操作！');
            return redirect('/building/'.$building->id.'/init');
        }
        return view('admin.building.create');
    }

    //宿舍楼_创建逻辑
    public function stroe(Request $request){
        //表单验证
        $this->validate($request,[
           'area' => 'required',
            'sex' => 'required',
            'building' => 'required',
            'layers' => 'required',
            'layer_roon_num' => 'required',
            'preference' => 'required',
            'verification' => 'required|captcha',
        ]);
        //查询楼号是否重复
        $building = Building::where('area',$request->area)->where('building',$request->building)->first();
        if(!empty($building)){
            session()->flash('danger','楼号已经存在，请检查您的填写！');
            return redirect()->back();
        }
        //授权
        $this->authorize('create',Building::class);
        //创建记录
        $empty_num = intval($request->layers) * intval($request->layer_roon_num);
        $info = Building::create([
            'area' => $request->area,
            'sex' => $request->sex,
            'building' => $request->building,
            'layers' => $request->layers,
            'layer_roon_num' => $request->layer_roon_num,
            'preference' => $request->preference,
            'empty_num' => $empty_num,
        ]);
        if(empty($info)){
            session()->flash('danger','出现未知错误，请稍后再试！');
            return redirect()->back();
        }
        //返回宿舍添加页
        session()->flash('success','添加大楼成功,请设置宿舍！');
        return redirect('/building/'.$info->id.'/init');
    }

    //宿舍楼_详情页
    public function show(Building $building){
        //防止异常操作
        if(empty($building)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //授权
        $this->authorize('look',Building::class);
        //计算使用的宿舍数
        $num = intval($building->layers) * intval($building->layer_roon_num) - intval($building->empty_num);
        //分割偏好中的专业代码
        $code = $building->preference;
        $code = explode(';',$code);
        //宿舍详情
        $dormitory = Dormitory::where('building_id',$building->id)->orderBy('house_num','asc')->pluck('id','house_num');
        return view('admin.building.show',compact('building','num','code','dormitory'));
    }

    //宿舍楼_修改页
    public function edit(Building $building){
        //防止异常操作
        if(empty($building)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //授权
        $this->authorize('update',Building::class);
        return view('admin.building.edit',compact('building'));
    }

    //宿舍楼_修改逻辑
    public function update(Request $request,Building $building){
        //防止异常操作
        if(empty($building)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //表单验证
        $this->validate($request,[
            'area' => 'required',
            'sex' => 'required',
            'building' => 'required',
            'preference' => 'required',
            'verification' => 'required|captcha',
        ]);
        //授权
        $this->authorize('update',Building::class);
        //修改
        $building->area = $request->area;
        $building->sex = $request->sex;
        $building->building = $request->building;
        $building->preference = $request->preference;
        $building->save();
        //成功，返回
        session()->flash('success','大楼信息修改成功！');
        return redirect(route('building_list'));
    }

    //宿舍楼_删除
    public function delete(Building $building){
        //防止异常操作
        if(empty($building)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //授权
        $this->authorize('delete',Building::class);
        $dormitory = Dormitory::where('building_id',$building->id)->delete();
        $building->delete();
        //成功，返回信息
        session()->flash('seccess','删除大楼成功！');
        return redirect(route('building_list'));
    }

    //宿舍楼_宿舍初始化页
    public function init($id){
        //授权
        $this->authorize('create',Building::class);
        return view('admin.building.init',compact('id'));
    }

    //宿舍楼_宿舍初始化逻辑
    public function initing(Building $building,Request $request){
        //如果大楼已经初始化，返回
        if($building->status){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //授权
        $this->authorize('general',Dormitory::class);
        //表单验证
        $this->validate($request,[
           'roon_num' => 'required',
            'verification' => 'required|captcha',
        ]);
        //获取大楼的层数，及每层的宿舍数
        $FloorNum = $building->layers;
        $LayerNum = $building->layer_roon_num;
        //检错
        if(empty($FloorNum) ||  empty($LayerNum)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //切割字符串，获得其余两种宿舍的宿舍号
        switch ($request->roon_num){
            case 4:
            case "4":
                $roon_num = 4;
                $code1 = [];
                $code2 = explode(';',$request->six_roon);
                $code3 = explode(';',$request->eight_roon);
                break;
            case 6:
            case "6":
                $roon_num = 6;
                $code1 = explode(';',$request->four_roon);
                $code2 = [];
                $code3 = explode(';',$request->eight_roon);
                break;
            case 8:
            case "8":
                $roon_num = 8;
                $code1 = explode(';',$request->four_roon);
                $code2 = explode(';',$request->six_roon);
                $code3 = [];
                break;
        }
        //跳过循环的标志
        $flag = 0;
        //循环插入宿舍记录
        for($i = 1; $i <= intval($building->layers); ++$i){
            for($j = 1; $j <= intval($building->layer_roon_num); ++$j){
                //拼接宿舍号
                if($j < 10){
                    $DormitoryNum = $i.'0'.$j;
                }else{
                    $DormitoryNum = $i.$j;
                }
                //将宿舍号与表单的特例宿舍号进行对比
                foreach ($code1 as $value) {
                    if ($DormitoryNum == $value) {
                        $dor = Dormitory::create([
                            'house_num' => $DormitoryNum,
                            'building_id' => $building->id,
                            'floor' => $i,
                            'num' => $j,
                            'roon_people' => 4,
                        ]);
                        if (empty($dor)) {
                            session()->flash('danger', '出现未知错误，请稍后再试！');
                            return redirect()->back();
                        }
                        $flag = 1;
                    }
                }
                foreach ($code2 as $value) {
                    if ($DormitoryNum == $value) {
                        $dor = Dormitory::create([
                            'house_num' => $DormitoryNum,
                            'building_id' => $building->id,
                            'floor' => $i,
                            'num' => $j,
                            'roon_people' => 6,
                        ]);
                        if (empty($dor)) {
                            session()->flash('danger', '出现未知错误，请稍后再试！');
                            return redirect()->back();
                        }
                        $flag = 1;
                    }
                }
                foreach ($code3 as $value) {
                    if ($DormitoryNum == $value) {
                        $dor = Dormitory::create([
                            'house_num' => $DormitoryNum,
                            'building_id' => $building->id,
                            'floor' => $i,
                            'num' => $j,
                            'roon_people' => 8,
                        ]);
                        if (empty($dor)) {
                            session()->flash('danger', '出现未知错误，请稍后再试！');
                            return redirect()->back();
                        }
                        $flag = 1;
                    }
                }
                //如果匹配成功，跳过一次循环
                if($flag){
                    $flag = 0;
                    continue;
                }
                //没有匹配，创建记录
                $dor = Dormitory::create([
                    'house_num' => $DormitoryNum,
                    'building_id' => $building->id,
                    'floor' => $i,
                    'num' => $j,
                    'roon_people' => $roon_num,
                ]);
                if (empty($dor)) {
                    session()->flash('danger', '出现未知错误，请稍后再试！');
                    return redirect()->back();
                }
            }
        }
        //标记大楼为已初始化状态
        $building->status = 1;
        $building->save();

        session()->flash('success', '初始化成功！');
        return redirect(route('building_list'));
    }
}
