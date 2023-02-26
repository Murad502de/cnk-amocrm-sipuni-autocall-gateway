<?php

namespace App\Http\Middleware\Services\AmoCrm;

use App\Exceptions\ForbiddenException;
use App\Models\AmoCRM;
use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!AmoCRM::all()->exists()) {
            throw new ForbiddenException("Access denied");
        }

        return $next($request);
    }
}
