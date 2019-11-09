<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendNotificationMail extends Notification implements ShouldQueue
{
    use Queueable;
    public $content1;
    public $url;
    public $title;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title,$content1,$url)
    {
        $this->title = $title;
        $this->content1 = $content1;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->title)
                    ->from('2793837438@qq.com', '北部湾大学小助手')
                    ->line($this->content1)
                    ->action('点击处理', $this->url)
                    ->line('此邮件为系统自动发送，请勿回复！');
    }
}
