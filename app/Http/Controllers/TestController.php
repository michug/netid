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

            if (App::environment('local')) {
               $oidc->setVerifyHost(false);
               $oidc->setVerifyPeer(false);
            }

            // default scope is "openid"
            $oidc->addScope(['profile']);
            $oidc->addAuthParam(array('claims'=>'{"userinfo":{"birthdate":{"essential":true},"gender":{"essential":true},"given_name":{"essential":true},"family_name":{"essential":true}}}'));

            // $oidc->setAllowImplicitFlow(true);
            // $oidc->addAuthParam(['response_mode' => 'form_post']);

            $this->printScopes($oidc); // DEBUG

            $oidc->authenticate();

            \Log::info('Requesting family_name to userInfo endpoint ...');
            $name = $oidc->requestUserInfo('family_name');

            \Log::info('Requesting sub to userInfo endpoint ...');
            $sub = $oidc->requestUserInfo('profile');
            \Log::info('    requested UserInfo sub: ' . $sub);

            // $customer = $this->getCustomer($email);
            // login $customer and redirect apropiatelly



            $this->printVerifiedClaims($oidc);

        } catch (\Exception $ex) {
            \Log::info($ex->getMessage());
        }
    }


    private function getCustomer($email)
    {
        // maybe we could use the netid client_id which relates user, netid and asuro-api
        $customer = Customer::findOrFail('email', $email)->first();

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
