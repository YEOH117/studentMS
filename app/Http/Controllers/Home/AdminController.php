<?php

namespace App\Http\Controllers\Home;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //管理员账号列表页
    public function list(){
        $this->authorize('view',User::class);
        $info = User::where('grade','>=',2)->paginate(10);
        return view('admin.admin.admin_list',compact('info'));
    }

    //管理员账号创建页
    public function create(){
        $this->authorize('view',User::class);
        return view('admin.admin.create');
    }

    //管理员账号创建逻辑
    public function stroe(Request $request){
        //表单验证
        $this->validate($request,[
            'name' => 'required|max:10',
            'account' => 'required|max:20|unique:users,account',
            'email' => 'nullable|email|max:25|unique:users,email',
            'phone' => 'max:12|unique:users,phone',
            'grade' => 'required',
            'password' => 'max:25|min:6|required|confirmed',
        ]);
        //授权
        $this->authorize('add',User::class);
        //创建管理员账号
        $user = new User();
        $user->account = $request->account;
        $user->name = $request->name;
        if($request->has('email') && !empty($request->email)){
            $user->email = $request->email;
        }
        if($request->has('phone') && !empty($request->phone)){
            $user->phone = $request->phone;
        }
        if(Auth::user()->grade > $request->grade){
            $user->grade = $request->grade;
        }else{
            session()->flash('danger','请按照使用说明操作！');
            return redirect()->back();
        }
        $user->password = bcrypt($request->password);
        $user->save();
        //返回信息
        session()->flash('success','账号创建成功！');
        return redirect()->back();
    }

    //管理员账号删除逻辑
    public function delete(User $user){
        //防止异常操作
        if(empty($user)){
            session()->flash('danger','地址错误！');
            return redirect('/');
        }
        //授权
        $this->authorize('delete',$user);
        //防止恶意操作
        if($user->grade <= 1){
            session()->flash('danger','请按照使用说明操作！');
            return redirect()->back();
        }
        $user->delete();
        //返回信息
        session()->flash('success','管理员账号删除成功！');
        return redirect()->back();
    }
}
