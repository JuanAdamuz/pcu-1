<?php

namespace SocialiteProviders\IPB;

use SocialiteProviders\Manager\SocialiteWasCalled;

class IPBExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('ipb', __NAMESPACE__.'\Provider');
    }
}
