<?php

namespace App\Listeners;

use App\Events\SendEmailActive;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailActiveListener
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
     * @param  SendEmailActive  $event
     * @return void
     */
    public function handle(SendEmailActive $event)
    {
        //获取用户与新密保邮箱
        $user = $event->user;
        $email = $event->email;
        //生成验证码
        $token = str_random(60);
        //邮件信息
        $content1 = '您的账号于('.date('G:i').')更换了密保邮箱，如确认是本人操作，请点击下面按钮完成邮箱确认与激活：';
        $content2 = '如果您未请求过更换密保邮箱，则无需采取进一步措施.';
        $button = '点击激活';
        $url = route('user_mail_set',[$email,$token]);
        //发送邮件
        Mail::send('emails.button',compact('content1','content2','button','url'),function($message) use ($email){
            $message->from('2793837438@qq.com','北部湾大学宿舍管理小助手')->to($email)->subject('北部湾大学宿舍管理系统通知');
        });
        //将token存入user表中
        $user->verifycode = $token;
        $user->save();
    }
}
