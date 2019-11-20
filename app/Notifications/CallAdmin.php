<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Notifications\CommonNotification;
use App\Events\CommNotification;
use Event;

class CallAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student)
    {
        $this->student = $student;
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

        $data = ['id'=>$notifiable->id, 'msg'=>$this->student, 'type'=>'scholarship'];

        Event::dispatch(new CommNotification($data));
        //Notification::send(Auth::user(), new CommonNotification($data));

        return (new MailMessage)
                    ->subject('New scholarship application')
                    ->greeting('Admin,')
                    ->line($this->student)
                    ->line('Pleas check it...')
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
            'msg'=>$this->student,
            'type'=>'scholarship'
        ];
    }
}
