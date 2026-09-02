<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Restrict access to users owning at least one of the given role codes.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->hasRole($roles)) {
            abort(Response::HTTP_FORBIDDEN, 'Accès refusé : rôle insuffisant.');
        }

        return $next($request);
    }
}
