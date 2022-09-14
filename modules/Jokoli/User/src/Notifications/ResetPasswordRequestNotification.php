<?php

namespace Jokoli\User\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Jokoli\User\Mail\ResetPasswordRequestMail;
use Jokoli\User\Mail\VerifyCodeMail;
use Jokoli\User\Rules\ValidPassword;
use Jokoli\User\Services\VerifyCodeService;

class ResetPasswordRequestNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $code = VerifyCodeService::generate();
        VerifyCodeService::store($notifiable->id,$code,now()->addMinutes(2));
        return (new ResetPasswordRequestMail($code))->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
