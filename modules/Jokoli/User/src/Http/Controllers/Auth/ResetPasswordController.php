<?php

namespace Jokoli\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Jokoli\User\Http\Requests\ChangePasswordRequest;
use Jokoli\User\Repository\UserRepository;
use Jokoli\User\Services\UserService;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request)
    {
        return view('User::front.passwords.reset');
    }

    public function reset(ChangePasswordRequest $request)
    {
        UserService::changePassword(auth()->user(),$request->password);
        return redirect()->route('home');
    }

}
