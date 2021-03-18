<?php

namespace App\Http\Controllers;

use \Log;
use Illuminate\Http\Request;

use \Jumbojett\OpenIDConnectClient;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function authenticate(Request $request)
    {
        /*\Log::info($request);*/

        try {
            $issuer = 'https://broker.netid.de';
            $cid = 'dcb4a1b4-0e9e-42a0-b282-7c160dfe8f43';
            $secret = 'sUMEthymJgCZTSiPFlDDYfjpuKYoX-j7x6u3vXp9tX1aE7hA3EidTBP8yU7457m';
            $oidc = new OpenIDConnectClient($issuer, $cid, $secret);

            // default scope is "openid"
            $oidc->addScope('openid profile email given_name');

            $this->printScopes($oidc); // DEBUG

            $oidc->authenticate();

            $name = $oidc->requestUserInfo('given_name');

            $email = $oidc->requestUserInfo('email');

            // $customer = $this->getCustomer($email);
            // login $customer and redirect apropiatelly


            $sub = $oidc->requestUserInfo('sub');
            \Log::info('requested UserInfo sub: ' . $value);

            $this->printVerifiedClaims($oidc);

        } catch (\Exception $ex) {
            \Log::info($ex->getMessage());
        }
    }


    private function getCustomer($email)
    {
        $customer = Customer::findOrFail('email', $email)->first();

        if (!$customer) {
            // $customer = register new customer
        }

        return $customer;
    }

    private function printVerifiedClaims($oidc)
    {
        foreach ($oidc->getVerifiedClaims() as $key => $value) {
            \Log::info($key . ': ' . $value);
        }
    }


    private function printScopes($oidc)
    {
        $scopes = $oidc->getScopes();
        foreach ($scopes as $scope) {
            \Log::info('scope: ' . $scope);
        }
    }
}
