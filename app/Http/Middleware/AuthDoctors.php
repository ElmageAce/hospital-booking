<?php

namespace App\Http\Middleware;

use Closure;

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
        $role = cache()->remember("user:{$request->user()->id}", now()->addDay(),
            function() use ($request){
                return $request->user()->role->slug;
            });

        if($role !== 'doctor')
            return abort(403);

        return $next($request);
    }
}
