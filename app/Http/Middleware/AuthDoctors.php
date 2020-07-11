<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class AuthDoctors
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed|void
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if(Gate::denies('doctor'))
            return abort(403, "You're not authorized to access this page");

        return $next($request);
    }
}
