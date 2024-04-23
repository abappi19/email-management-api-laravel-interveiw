<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailErrorNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $subject;
    protected $errorMessage;

    public function __construct($subject, $errorMessage)
    {
        $this->subject = $subject;
        $this->errorMessage = $errorMessage;
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Email could not sent')
            ->line('Email: ' . $this->subject . ' could not sent. reason: ' . $this->errorMessage)
            ->action('View Notification', url('/'));
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => 'Failed to send email with subject: "' . $this->subject . '". Reason: ' . $this->errorMessage
        ];
    }
}
