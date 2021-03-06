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
            $oidc->addScope(['profile']);
            $oidc->addAuthParam(array('claims'=>'{"userinfo":{"email":{"essential":true},"address":{"essential":true},"birthdate":{"essential":true},"phone_number":{"essential":true},"given_name":{"essential":true},"family_name":{"essential":true}}}'));

            $oidc->authenticate();

            $userInfo = $oidc->requestUserInfo();

            $customer = $this->getCustomer($userInfo);
            // login $customer and redirect apropiatelly



            $this->printVerifiedClaims($oidc);

        } catch (\Exception $ex) {
            \Log::info($ex->getMessage());
        }
    }


    private function getCustomer($userInfo)
    {
        // maybe we could use the netid client_id which relates user, netid and asuro-api
        $customer = Customer::findOrFail('email', $userInfo['email'])->first();

        if (!$customer) {
            // $customer = register new customer
        }

        return $customer;
    }

    private function printVerifiedClaims($oidc)
    {
        \Log::info(' ');
        \Log::info('--- VERIFIED CLAIMS -----');
        foreach ($oidc->getVerifiedClaims() as $key => $value) {
            \Log::info($key . ': ' . $value);
        }
    }


    private function printScopes($oidc)
    {
        \Log::info('-- SCOPES -- ');
        $scopes = $oidc->getScopes();
        foreach ($scopes as $scope) {
            \Log::info('    scope: ' . $scope);
        }
        \Log::info(' ');
    }
}
