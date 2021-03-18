<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Jumbojett\OpenIDConnectClient;

class TestController extends Controller
{
    public function authenticate(Request $request)
    {
        \Log::info($request);

        try {
            $issuer = 'https://broker.netid.de';
            $cid = 'dcb4a1b4-0e9e-42a0-b282-7c160dfe8f43';
            $secret = 'sUMEthymJgCZTSiPFlDDYfjpuKYoX-j7x6u3vXp9tX1aE7hA3EidTBP8yU7457m';
            $oidc = new OpenIDConnectClient($issuer, $cid, $secret);
            $oidc->addScope('openid profile');
            // $oidc->addScope(['email', 'given_name']);

            $scopes = $oidc->getScopes();
            foreach ($scopes as $scope) {
                \Log::info('scope: ' . $scope);
            }

            $oidc->authenticate();
            $name = $oidc->requestUserInfo('given_name');
            $sub = $oidc->requestUserInfo('sub');

            foreach ($oidc->getVerifiedClaims() as $key => $value) {
                \Log::info($key . ' ' . $value);
            }

            \Log::info('sub ' . $sub);
            \Log::info('name ' . $name);

        } catch (\Exception $ex) {
            \Log::error($ex);
        }
    }
}
