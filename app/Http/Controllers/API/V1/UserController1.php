<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\UserSigninRequest1;
use App\Models\User;

class UserController1 extends Controller
{
    public function signin(UserSigninRequest1 $request)
    {
        $user = User::where('email', $request->email)
            ->where('password', md5($request->password))
            ->first();

        return [
            'token' => $user->access_token,
            'uuid'  => $user->uuid,
        ];
    }
}
