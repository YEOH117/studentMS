<?php

namespace App\Listeners;

use App\Events\SendEmailVerifyCode;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerifyCodeListener implements ShouldQueue
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
     * @param  SendEmailVerifyCode  $event
     * @return void
     */
    public function handle(SendEmailVerifyCode $event)
    {
        //获取用户
        $user = $event->user;
        //生成验证码
        $code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        //将验证码存入user表中
        $user->verifycode = $code;
        $user->save();
        //邮件信息
        $content1 = '您的账号于('.date('G:i').')申请更换密保邮箱，如确认是本人操作，请提交以下验证码完成操作：';
        $content2 = '如果您未请求更换密保邮箱，则无需采取进一步措施.';
        //发送邮件
        Mail::send('emails.text',compact('content1','content2','code'),function($message) use ($user){
            $message->from('18965720201@163.com','北部湾大学宿舍管理小助手')->to($user->email)->subject('北部湾大学宿舍管理系统通知');
        });
    }
}
