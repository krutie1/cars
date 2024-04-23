<?php

namespace App\Http\Middleware;

use App\Enums\UserRolesEnum;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->hasRole(UserRolesEnum::ADMIN)) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
