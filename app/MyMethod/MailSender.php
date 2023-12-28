<?php

namespace App\MyMethod;

use App\Mail\NotificationMail;
use Exception;
use Illuminate\Support\Facades\Mail;

class MailSender
{
    public static function sendMailer($data, $email, $email_blade)
    {
        try {
            $data['email_blade'] = $email_blade;
            Mail::to($email)->send(new NotificationMail($data));
            return true;
        } catch (Exception  $err) {
            return false;
        }
    }
}
