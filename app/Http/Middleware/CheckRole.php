<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if(Auth::user() === null){
            return response('Access denied',401);
        }

        $actions = $request->route()->getAction();
        
        $roles = isset($actions['roles'])?$actions['roles']:null;
         
        if(Auth::user()->hasAnyRole($roles) || !$roles){
            return $next($request);
        }
       
        return response('Access denied',401);
    }
}
