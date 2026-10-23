<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        abort_unless(
            $user && $user->role === UserRole::tryFrom($role),
            Response::HTTP_FORBIDDEN
        );

        return $next($request);
    }
}
