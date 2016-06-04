<?php

namespace ZEDx\Http\Controllers\Frontend\User\Auth;

class SocialProviderController extends SocialiteController
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($providerName)
    {
        if ($this->validProvider($providerName)) {
            return $this->redirectTo($providerName);
        }

        abort(404);
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($providerName)
    {
        if ($this->validProvider($providerName)) {
            return $this->handleProvider($providerName);
        }

        abort(404);
    }

    /**
     * Check whether the provider exists and enabled.
     *
     * @param string $providerName
     *
     * @return bool
     */
    protected function validProvider($providerName)
    {
        $providers = json_decode(setting('social_auths'));

        if (!$providers->{$providerName}) {
            return false;
        }

        return $providers->{$providerName}->enabled;
    }
}
