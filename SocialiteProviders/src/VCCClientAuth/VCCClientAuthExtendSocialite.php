<?php

namespace SocialiteProviders\VCCClientAuth;

use SocialiteProviders\Manager\SocialiteWasCalled;

class VCCClientAuthExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param  \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('vcc-client-auth', Provider::class);
    }
}
