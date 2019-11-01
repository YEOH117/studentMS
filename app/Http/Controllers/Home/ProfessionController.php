<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profession_code;

class ProfessionController extends Controller
{
    //专业代码列表
    public function list(){
        $this->authorize('general',Profession_code::class);
        $info = Profession_code::simplePaginate(10);
        return view('admin.profession.list',compact('info'));
    }

    //专业代码_创建页
    public function create(){
        $this->authorize('general',Profession_code::class);
        return view('admin.profession.create');
    }

    //专业代码_创建逻辑
    public function stroe(Request $request){
        //表单验证
        $this->validate($request,[
           'college' => 'required',
            'profession' => 'required',
            'code' => 'required|max:5|min:5|unique:professions_code,code',
            'verification' => 'required|captcha',
        ]);
        //授权
        $this->authorize('create',Profession_code::class);
        //创建专业代码记录
        $code = Profession_code::create([
           'college' => $request->college,
           'profession' => $request->profession,
           'code' => $request->code,
        ]);
        //如果出错，提示
        if(empty($code)){
            session()->flash('danger','出现未知错误，请稍后再试！');
            return redirect()->back();
        }
        //成功，提示
        session()->flash('success','创建专业代码成功！');
        return redirect(route('profession_list'));
    }

    //专业代码_修改页
    public function edit(Profession_code $profession_code){
        //防止异常操作
        if(empty($profession_code)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //授权
        $this->authorize('edit',Profession_code::class);

        return view('admin.profession.edit',compact('profession_code'));
    }

    //专业代码_修改逻辑
    public function update(Request $request,Profession_code $profession_code){
        //防止异常操作
        if(empty($profession_code)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //表单验证
        $this->validate($request,[
            'college' => 'required',
            'profession' => 'required',
            'code' => 'required|max:5|min:5',
            'verification' => 'required|captcha',
        ]);
        //授权
        $this->authorize('create',Profession_code::class);
        if(empty($profession_code)){
            //错误，提示
            session()->flash('danger','出现错误，请稍后再试！');
            return redirect(route('profession_list'));
        }
        //更新数据
        $profession_code->college = $request->college;
        $profession_code->profession = $request->profession;
        $profession_code->code = $request->code;
        $profession_code->save();
        //成功，提示
        session()->flash('success','更新专业代码成功！');
        return redirect(route('profession_list'));
    }
}
