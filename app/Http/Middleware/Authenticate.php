<?php

namespace App\Http\Middleware;

use App\Enums\UserRolesEnum;
use App\Http\Middleware\Userer as UsererAlias;
use App\Models\User;
use Closure;
use DateTime;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        /** @var User $user */
        $user = $request->user();

        if (empty($guards)) {
            $guards = [null];
        }

        if ($user && $request->session()->has('last_login')) {
            $lastLogin = $request->session()->get('last_login');

            if (!$user->hasRole(UserRolesEnum::ADMIN)) {
                if (now()->setTime(0, 0) > (new DateTime($lastLogin))->setTime(0, 0)) {
                    Auth::logout();

                    throw new AuthenticationException(
                        'Unauthenticated.', [], route('login')
                    );
                }
            }
        }

        return parent::handle($request, $next, ...$guards);
    }
}
