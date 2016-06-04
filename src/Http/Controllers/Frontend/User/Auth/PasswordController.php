<?php

namespace ZEDx\Http\Controllers\Frontend\User\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Mail\Message;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Services\Frontend\PageService;

class PasswordController extends Controller
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
     * Where to redirect users after password reset.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the Closure which is used to build the password reset email message.
     *
     * @return \Closure
     */
    protected function resetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject(trans('email.user.password_reset.subject'));
        };
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $page = (object) (new PageService())->show('auth.password.email', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showResetForm()
    {
        $page = (object) (new PageService())->show('auth.password.reset', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     *
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password'       => $password,
            'remember_token' => str_random(60),
        ])->save();

        \Auth::guard($this->getGuard())->login($user);
    }
}
