<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $title, $message, $source_id, $source_type, $web_link, $api_link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $source_id, $source_type, $web_link, $api_link)
    {
        $this->title = $title;
        $this->message = $message;
        $this->source_id = $source_id;
        $this->source_type = $source_type;
        $this->web_link = $web_link;
        $this->api_link = $api_link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'source_id' => $this->source_id,
            'source_type' => $this->source_type,
            'web_link' => $this->web_link,
            'api_link' => $this->api_link
        ];
    }
}
