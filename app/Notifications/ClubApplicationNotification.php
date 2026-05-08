<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClubApplicationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string $icon
     * @param string $link
     */
    public function __construct(
        public string $title,
        public string $message,
        public string $icon = '🔔',
        public string $link = '#'
    ) {}

    /**
     * Notification delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data stored in notifications table
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'icon'    => $this->icon,
            'link'    => $this->link,
        ];
    }
}