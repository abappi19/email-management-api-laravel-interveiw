<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Email;
use App\Models\Group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EmailController extends Controller
{

    function upsert()
    {
        // $payload = request()->getPayload()->all();


        // 'id',
        // 'timestamp',
        // 'subject',
        // 'body',
        // 'sender_email',
        // 'recipient_email',
        // 'format',

        try {
            $validatedData = request()->validate([
                'subject' => 'required|string',
                'body' => 'required|string',
                'sender_email' => 'required|email',
                'recipient_email' => 'required|array',
                'recipient_email.*' => 'email',
                'format' => 'string',
                'id' => 'numeric'
            ]);

            $validatedData['format'] = $validatedData['format'] ?? 'text';
            $validatedData['recipient_email'] = json_encode($validatedData['recipient_email']);

            $email = Email::updateOrCreate(['id' => $validatedData['id']], $validatedData);

            $msg = ($email->id === $validatedData['id']) ? 'updated' : 'added';

            return $this->success("Email " . $msg . " successfully", 201);
        } catch (ValidationException $e) {
            // Validation failed, return custom response
            return $this->error('Validation failed: ' . $e->getMessage(), 422);
        }
    }

    function get(Request $req, $id)
    {

        try {
            $email  = Email::findOrFail($id);
            return $this->success($email);
        } catch (Exception $e) {
            return $this->error('Email not found', 404);
        }
    }

    function delete(Request $req, $id)
    {
        try {

            $email  = Email::findOrFail($id);
            $email->delete();

            return $this->success("Email has been deleted successfully");
        } catch (Exception $e) {
            return $this->error('No email found to delete', 404);
        }
    }
    function sendMail()
    {
        $payload = request()->getPayload()->all();


        if (isset($payload['email_ids'])) {

            return $this->sendEmailFromEmailIds($payload['email_ids']);
        }
        if (isset($payload['group_id'])) {
            return $this->sendEmailFromGroupId($payload['group_id']);
        }
    }

    function sendEmailFromEmailIds($emailIds)
    {
        try {
            // Dispatch a job for each email ID
            foreach ($emailIds as $emailId) {
                $email = Email::findOrFail($emailId);
                SendEmailJob::dispatch($email);
            }

            return $this->success('Emails queued for sending', 200);
        } catch (Exception $e) {
            return $this->error('Failed to send email. error: ' . $e->getMessage(), 500);
        }
    }
    function sendEmailFromGroupId($groupId)
    {

        try {
            // Retrieve email IDs associated with the group
            $group = Group::findOrFail($groupId);
            $emailIds = $group->emails()->pluck('id')->toArray();

            // Dispatch a job for each email ID
            foreach ($emailIds as $emailId) {
                $email = Email::findOrFail($emailId);
                SendEmailJob::dispatch($email);
            }

            return $this->success('Emails queued for sending', 200);
        } catch (Exception $e) {
            return $this->error('Failed to send email. error: ' . $e->getMessage(), 500);
        }
    }

    //
}
