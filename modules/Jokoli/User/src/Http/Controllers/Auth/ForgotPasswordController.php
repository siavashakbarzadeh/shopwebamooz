<?php

namespace Jokoli\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Jokoli\User\Http\Requests\ResetPasswordVerifyCodeRequest;
use Jokoli\User\Http\Requests\SendResetPasswordVerifyCodeRequest;
use Jokoli\User\Http\Requests\VerifyCodeRequest;
use Jokoli\User\Models\User;
use Jokoli\User\Repository\UserRepository;
use Jokoli\User\Services\VerifyCodeService;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showVerifyCodeRequestFrom()
    {
        return view('User::front.passwords.email');
    }

    public function sendVerifyCodeEmail(SendResetPasswordVerifyCodeRequest $request)
    {
        $user = resolve(UserRepository::class)->findByEmail($request->email);
        if ($user && !VerifyCodeService::has($user->id)) $user->sendRestPasswordRequestNotification();
        return view('User::front.passwords.enter-verify-code-form');
    }

    public function checkVerifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        $user = resolve(UserRepository::class)->findByEmail($request->email);
        if (!$user || !VerifyCodeService::check($user->id, $request->verify_code))
            return redirect()->route('password.sendVerifyCodeEmail')
                ->withErrors(['verify_code' => "کد فعالسازی معتبر نمیباشد"]);
        auth()->loginUsingId($user->id);
        return redirect()->route('password.showResetForm');
    }
}
