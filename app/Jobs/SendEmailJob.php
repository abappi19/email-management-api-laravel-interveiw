<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Email;
use App\Models\User;
use App\Notifications\EmailErrorNotification;
use App\Notifications\EmailSentNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Email $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        try {
            $data = [
                'body' => $this->email->body,
                'subject' => $this->email->subject,
                'format' => $this->email->format,
                'sender_email' => $this->email->sender_email
            ];


            // Log::debug('Attempting to send email: ' . $data['subject']);

            // // Send the email 
            // Mail::to(json_decode($this->email->recipient))
            //     ->send(new SendMail($data));

            // Mail::send([], [], function ($message) {
            //     $message->to(json_decode($this->email->recipient))
            //         ->subject($this->email->subject ?? 'No Subject')
            //         // here comes what you want
            //         ->setBody($this->email->body ?? '', $this->email->format === 'html' ? 'text/html' : null);
            // });

            $recipient = json_decode($this->email->recipient_email);


            if (is_string($recipient) || (is_array($recipient) && !empty($recipient))) {
                Mail::to($recipient)
                    ->send(new SendMail($data));
                //mail has been sent... notify user

                $this->notifyMailSent($data);

            } else {
                // Handle empty or invalid recipient address
                // Log::error('Invalid recipient address: ' . $recipient);
                // Optionally, you can throw an exception or handle the error as needed
                $this->notifyMailError($data, 'Invalid recipient address: ' . $recipient);
            }
        } catch (Exception $e) {
            // Log::debug('Failed to send email: ' . $data['subject'] . ' reason: ' . $e->getMessage());
            $this->notifyMailError($data, $e->getMessage());
        }
    }

    function notifyMailSent($data)
    {
        // Notify the user about the email
        $senderUser = User::where('email', $data['sender_email'])->first();
        if ($senderUser) {
            Notification::send($senderUser, new EmailSentNotification($data['subject']));
        } else {
            Log::warning('User not found for sender email: ' . $data['sender_email']);
        }
    }
    function notifyMailError($data, String $e)
    {
        // Notify the user about the email
        $senderUser = User::where('email', $data['sender_email'])->first();
        if ($senderUser) {
            Notification::send($senderUser, new EmailErrorNotification($data['subject'], $e));
        } else {
            Log::warning('User not found for sender email: ' . $data['sender_email']);
        }
    }
}
