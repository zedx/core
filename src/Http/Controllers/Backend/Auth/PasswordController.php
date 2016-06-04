<?php

namespace ZEDx\Http\Controllers\Backend\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Mail\Message;
use ZEDx\Http\Controllers\Controller;

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
     * Link to request view.
     *
     * @var string
     */
    protected $linkRequestView = 'backend.auth.password';

    /**
     * Reset view.
     *
     * @var string
     */
    protected $resetView = 'backend.auth.reset';

    /**
     * Which guard will be used to reset password.
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * The password broker that should be used.
     *
     * @var string
     */
    protected $broker = 'admin';

    /**
     * Where to redirect users after password reset.
     *
     * @var string
     */
    protected $redirectTo = '/zxadmin';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    /**
     * Get the Closure which is used to build the password reset email message.
     *
     * @return \Closure
     */
    protected function resetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject(trans('email.admin.password_reset.subject'));
        };
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string                                      $password
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
