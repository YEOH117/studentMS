<?php

namespace App\Http\Controllers\Home;

use App\Events\SendEmailActive;
use App\Events\SendEmailVerifyCode;
use App\Events\RestPassWordEmail;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //用户资料页
    public function index(){
        if(Auth::check()){
            $user = Auth::user();
            $student = Student::where('the_student_id',$user->account)->first();
            if(empty($student)){
                $student = [];
            }
            return view('user.index',compact('student','user'));
        }else{
            session()->flash('danger','你尚未登陆！');
            return redirect('/login');
        }

    }

    //用户修改资料 逻辑
    public function store(Request $request){
        //表单验证
        $this->validate($request,[
           'email' => 'required',
            'phone' => 'required',
        ]);
        $user = Student::where('the_student_id',Auth::user()->account)->first();
        if(empty($user)){
            session()->flash('danger','出现未知错误，保存失败！');
            return redirect()->back();
        }
        //修改信息
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        session()->flash('success','保存修改成功！');
        return redirect(route('user_me'));
    }

    //用户头像上传
    public function upload(Request $request){
        //表单验证
        $this->validate($request,[
           'upfile' => 'required',
        ]);
        //定义上传格式
        $extension = array('bmp','jpg','png','webp','jpeg');
        if($request->hasFile('upfile')) {
            $file = $request->file('upfile');
            $ext = $file->getClientOriginalExtension();
            if (in_array($ext, $extension)) {
            } else {
                //不符合，报错
                session()->flash('danger', '上传的文件格式错误！请上传文件格式为bmp、jpg、png、webp、jpeg的图片文件，其他格式文件暂不支持！');
                return redirect()->back();
            }
        }
        //重新命名文件
        $name = date('Y-m-d-h-i-s').rand(0,9).'.'.$ext;
        //保存文件，并获取其路径
        $path = $file->storeAs('public/avatar', $name);
        $path = str_replace('public','/storage',$path);
        //修改用户头像
        $user = Auth::user();
        $user->avatar = $path;
        $user->save();
        //返回
        session()->flash('success','头像修改成功！');
        return redirect(route('user_me'));
    }

    //邮箱设置页
    public function setEmail(){
        $user = Auth::user();
        if($user->email == '未绑定邮箱'){
            //如果用户没有初始化邮箱，进入初始化邮箱页
            return view('user.initEmail');
        }else{
            //如果用户设置了邮箱，进入邮箱修改页页
            return view('user.editEmail');
        }
    }

    //ajax通信，给原邮箱发送验证码
    public function sendVerifyEmail(){
        $user = Auth::user();
        //触发事件
        event(new SendEmailVerifyCode($user));
    }

    //给邮箱发送激活邮件
    public function sendActiveEmail(Request $request){
        //表单验证
        $this->validate($request,[
            'verify' => 'required|min:5|max:5',
            'email' => 'email|required',
        ]);
        //获取用户
        $user = Auth::user();
        //检查验证码是否一致
        if($user->verifycode != $request->verify){
            session()->flash('danger','验证码错误！');
            return redirect()->back();
        }
        //触发事件
        event(new SendEmailActive($user,$request->email));
        session()->flash('success','密保邮箱更换成功，请在新密保邮箱中查看激活邮件，并激活邮箱。');
        return redirect('/');
    }

    //给初始化邮箱发送激活邮件
    public function sendInitEmail(Request $request){
        //获取用户
        $user = Auth::user();
        //触发事件
        event(new SendEmailActive($user,$request->email));
        session()->flash('success','密保邮箱设置成功，请在密保邮箱中查看激活邮件，并激活邮箱。');
        return redirect('/');
    }

    //确认激活逻辑
    public function emailSeting($email,$token){
        //获取用户
        $user = Auth::user();
        //如果token不匹配
        if($user->verifycode != $token){
            session()->flash('danger','你无权访问！');
            return redirect('/');
        }
        $user->email = $email;
        $user->verifycode = '';
        $user->save();
        session()->flash('success','邮箱激活成功！');
        return redirect('/');

    }

    //修改密码页-通过原密码修改
    public function password(){
        if(Auth::check()){
            return view('user.password.index');
        }
    }

    //修改密码逻辑
    public function passwordUpdata(Request $request){
        //表单验证
        $this->validate($request,[
           'oldPassWord' => 'required',
            'password' => 'confirmed|required|min:6|max:30',
            'verification' => 'required|captcha'
        ]);

        //获取当前用户
        $user = Auth::user();
        //验证密码是否正确
        if(Auth::attempt(['account' => $user->account, 'password' => $request->oldPassWord, ])){
            //修改密码
            $user->password = bcrypt($request->password);
            $user->save();
            //让用户重新登录
            Auth::logout();
            session()->flash('success','密码修改成功，请重新登陆！');
            return redirect('/login');
        }else{
            session()->flash('danger','原密码错误！');
            return redirect()->back();
        }
    }

    //重置密码页
    public function passwordReset(){
        return view('user.password.reset');
    }

    //发送重置密码邮件
    public function resetPassWord(Request $request){
        //表单验证
        $this->validate($request,[
            'protection' => 'required',
            'account' => 'required',
            'verification' => 'required|captcha',
        ]);
        //查询用户
        $user = User::where('account',$request->account)->first();
        //选择密保方式
        if($request->protection){

        }else{
            //发送重置密码邮件
            event(new RestPassWordEmail($user));
        }
    }

    //填写密码密码页
    public function passwordReseting(User $user,$token){
        //验证token是否一致
        if($user->remember_token != $token){
            session()->flash('danger','你无权进行此操作！');
            return redirect('/login');
        }
        return view('user.password.updata',compact('user','token'));
    }

    //重置密码逻辑
    public function reset(Request $request){
        //表单验证
        $this->validate($request,[
            'password' => 'required|confirmed|min:6|max:30',
        ]);
        $user = User::find($request->user);
        //验证token是否一致
        if($user->remember_token != $request->token){
            session()->flash('danger','你无权访问！');
            return redirect()->back();
        }
        //更新密码
        $user->password = bcrypt($request->password);
        $user->save();

        session()->flash('success','密码设置成功，请重新登陆!');
        return redirect('/login');
    }

}
