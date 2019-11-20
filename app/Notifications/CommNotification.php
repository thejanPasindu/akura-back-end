<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Events\BroadcastNotificationCreated;

class CommNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'comm_notification' => 'Hellow world...!'
    //     ];
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'comm_notification' => $this->data
        ];


        
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'comm_notification' => $this->data
    //     ]);
    // }

    // public function broadcastAs($notifiable)
    // {
    //     return new Channel('commMsg.'.$notifiable->id);
    // }
    
}
