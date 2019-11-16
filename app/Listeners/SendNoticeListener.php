<?php

namespace App\Listeners;

use App\Events\SendNotice;
use App\Notifications\NotificationToStudent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNoticeListener implements ShouldQueue
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
     * @param  SendNotice  $event
     * @return void
     */
    public function handle(SendNotice $event)
    {
        //获取用户
        $user = $event->user;
        //标题
        $title = '宿舍管理通知';
        //内容
        $content = $event->notice;
        //拼接邮件内容
        $content1 = '宿舍管理员下发一条通知，内容如下：';
        $content2 = $content;
        //如果设置了邮箱
        if($user->email != '未绑定邮箱'){
            //发送邮件
            Mail::send('emails.simpleText',compact('content1','content2'),function($message) use ($user){
                $message->from('18965720201@163.com','北部湾大学宿舍管理小助手')->to($user->email)->subject('北部湾大学宿舍管理系统通知');
            });
        }
        //发送通知
        $user->notify(new NotificationToStudent($title,$content));
    }
}
