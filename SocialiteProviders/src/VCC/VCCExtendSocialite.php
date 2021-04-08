<?php

namespace SocialiteProviders\VCC;

use SocialiteProviders\Manager\SocialiteWasCalled;

class VCCExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param  \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('vcc', Provider::class);
    }
}
