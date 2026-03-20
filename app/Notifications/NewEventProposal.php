<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewEventProposal extends Notification
{
    use Queueable;

    public $event;

    // Pass the newly created event into the notification
    public function __construct($event)
    {
        $this->event = $event;
    }

    // Tell Laravel to store this in the database (instead of sending an email)
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // The actual data that gets saved to the database
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'message' => 'A new event proposal requires your approval.',
        ];
    }
}