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
            $oidc = new \Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

            $oidc->authenticate();
            $pepe = $oidc->requestUserInfo('given_name');
            // $pepe = $oidc->requestUserInfo('sub');
            \Log::info('$pepe');
            \Log::info($pepe);

        } catch (\Exception $ex) {
            Log::error($ex);
        }
    }
}
