<?php

namespace App\Listeners;

use App\Events\RestPassWordEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RestPassWordEmailListener
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
     * @param  RestPassWordEmail  $event
     * @return void
     */
    public function handle(RestPassWordEmail $event)
    {
        //获取用户与新密保邮箱
        $user = $event->user;
        //生成验证码
        $token = str_random(60);
        //邮件信息
        $content1 = '您的账号于('.date('G:i').')申请重置密码，如确认是本人操作，请点击下面按钮进行密码重置：';
        $content2 = '如果您未请求过更换密保邮箱，则无需采取进一步措施.';
        $button = '重置密码';
        $url = route('user_password_reseting',[$user->id,$token]);
        //发送邮件
        Mail::send('emails.button',compact('content1','content2','button','url'),function($message) use ($user){
            $message->from('18965720201@163.com','北部湾大学宿舍管理小助手')->to($user->email)->subject('北部湾大学宿舍管理系统通知');
        });
        //将token存入user表中
        $user->remember_token = $token;
        $user->save();
    }
}
