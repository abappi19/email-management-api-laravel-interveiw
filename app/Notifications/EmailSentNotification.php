<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailSentNotification extends Notification
{
    use Queueable;

    protected $subject;

    /**
     * Create a new notification instance.
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //using mail for notification
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Email sent')
            ->line('Email: '. $this->subject . ' has been sent.')
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
            'message' => 'Your email with subject: "' . $this->subject . '" has been sent successfully.'
        ];
    }
}
