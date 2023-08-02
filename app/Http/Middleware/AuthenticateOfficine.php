<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateOfficine
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()->is_admin, 403, 'This action is unauthorized.');

        return $next($request);
    }
}
