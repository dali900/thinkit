<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $user = Auth::guard('sanctum')->user();
		$rolesArray = explode("|", $roles);
        if (in_array($user->role, $rolesArray)) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}
