<?php

namespace App\Http\Controllers\Home;

use App\Models\Building;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuildingController extends Controller
{
    //宿舍楼_列表页
    public function list(){
        $this->authorize('look',Building::class);
        $area_0 = Building::where('area',0)->get();
        $area_1 = Building::where('area',1)->get();
        return view('admin.building.list',compact('area_0','area_1'));
    }

    //宿舍楼_创建页
    public function create(){
        $this->authorize('create',Building::class);
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
        session()->flash('success','添加大楼成功！');
        return redirect(route('building_list'));
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
        return view('admin.building.show',compact('building','num','code'));
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
            'layers' => 'required',
            'layer_roon_num' => 'required',
            'preference' => 'required',
        ]);
        //授权
        $this->authorize('update',Building::class);
        //修改
        $building->area = $request->area;
        $building->sex = $request->sex;
        $building->building = $request->building;
        $building->layers = $request->layers;
        $building->layer_roon_num = $request->layer_roon_num;
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
        $building->delete();
        //成功，返回信息
        session()->flash('seccess','删除大楼成功！');
        return redirect(route('building_list'));
    }
}
