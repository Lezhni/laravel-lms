<?php

namespace Modules\Api\Observers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Api\Models\AccessToken;

/**
 * Class AccessTokenObserver
 * @package Modules\Api\Observers
 */
class AccessTokenObserver
{
    /**
     * @param \Modules\Api\Models\AccessToken $accessToken
     */
    public function creating(AccessToken $accessToken)
    {
        $accessToken->key = Hash::make($accessToken->title . Carbon::now());
    }
}