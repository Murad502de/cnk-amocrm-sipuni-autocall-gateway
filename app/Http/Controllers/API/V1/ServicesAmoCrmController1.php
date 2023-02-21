<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AmoCRM;
use App\Services\amoAPI\amoAPIHub;
use App\Http\Requests\API\V1\ServicesAmoCrmAuthRequest1;

class ServicesAmoCrmController1 extends Controller
{
    public function signin(ServicesAmoCrmAuthRequest1 $request)
    {
        $authData = [
            'client_id'     => $request->all()['client_id'],
            'client_secret' => config('services.amoCRM.client_secret'),
            'code'          => $request->all()['code'],
            'redirect_uri'  => config('services.amoCRM.redirect_uri'),
            'subdomain'     => config('services.amoCRM.subdomain'),
        ];
        $amo         = new amoAPIHub($authData);
        $response    = $amo->auth();
        $accountData = [
            'client_id'     => $request->all()['client_id'],
            'client_secret' => config('services.amoCRM.client_secret'),
            'subdomain'     => $authData['subdomain'],
            'access_token'  => $response['access_token'],
            'redirect_uri'  => $authData['redirect_uri'],
            'token_type'    => $response['token_type'],
            'refresh_token' => $response['refresh_token'],
            'when_expires'  => time() + (int) $response['expires_in'] - 400,
        ];

        AmoCRM::auth($accountData);

        return response(['OK'], 200);
    }
    public function signout()
    {
        return response(['OK'], 200);
    }
}
