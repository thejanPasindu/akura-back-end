<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Events\CommNotification;
use Event;



class ReportUploadReminder extends Notification implements ShouldQueue
{
    use Queueable;
    protected $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = ['id'=>$notifiable->id, 'msg'=>$this->text, 'type'=>'reportUploadRem'];

        Event::dispatch(new CommNotification($data));

        return (new MailMessage)
                    ->greeting($notifiable->fname.',')
                    ->line($this->text)
                    ->line('Pleas Upload It')
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $notifiable->id,
            'msg'=>$this->text,
            'type'=>'reportUploadRem'
        ];
    }
}
