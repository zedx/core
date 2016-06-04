<?php

namespace ZEDx\Http\Controllers\Frontend\User\Auth;

use Auth;
use Exception;
use Socialite;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Models\User;
use ZEDx\Services\Frontend\User\UserService;

abstract class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Driver authentication page.
     *
     * @return Response
     */
    public function redirectTo($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from Driver.
     *
     * @return Response
     */
    public function handleProvider($driver)
    {
        try {
            $userProvider = Socialite::driver($driver)->user();

            return $this->syncProviderUser($driver, $userProvider);
        } catch (Exception $e) {
            return redirect()->route('user.register');
        }
    }

    protected function syncProviderUser($driver, $userProvider)
    {
        $userProviderId = $userProvider->getId();
        $user = Auth::user();
        $socialId = $driver.'_id';

        $userAlreadyConnectedToProvider = User::where($socialId, '=', $userProviderId)->first();

        if ($userAlreadyConnectedToProvider) {
            Auth::loginUsingId($userAlreadyConnectedToProvider->id);
        } elseif (Auth::check()) {
            $this->connectUsertoProvider($user, $socialId, $userProviderId);
        } else {
            $request = [
                'email'       => $userProvider->getEmail() ? $userProvider->getEmail() : $userProviderId.'@'.$driver,
                'password'    => str_random(10),
                'name'        => $userProvider->getName(),
                'is_validate' => $userProvider->getEmail() ? '1' : '0',
            ];

            $userService = new UserService();
            $item = $userService->store($request, ucfirst($driver));
            $user = $item['user'];

            $userService->makeAvatarFor($user, $userProvider->getAvatar());

            $this->connectUsertoProvider($user, $socialId, $userProviderId);
            Auth::loginUsingId($user->id);
        }

        return redirect()->route('user.ad.index');
    }

    protected function connectUsertoProvider($user, $socialId, $userProviderId)
    {
        $user->$socialId = $userProviderId;
        $user->save();
    }
}
