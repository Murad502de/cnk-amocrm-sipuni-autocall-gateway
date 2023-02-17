<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            $user = User::where('access_token', $request->bearerToken())->first();

            if ($user) {
                $user->updated_at = date('Y-m-d H:i:s');
                $user->save();

                Config::set('user', $user);

                return $next($request);
            }
        }

        throw new ForbiddenException('Token is not valid.');
    }
}
