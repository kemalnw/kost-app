<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class Owner
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
        if (!$request->user()->hasRole(Role::OWNER)) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
