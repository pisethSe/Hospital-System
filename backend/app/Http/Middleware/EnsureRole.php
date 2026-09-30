<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role gate for the API. Roles map to the legacy login tables:
 * "admin"  -> his_admin
 * "doctor" -> his_docs
 *
 * Usage: ->middleware('role:admin') or ->middleware('role:admin,doctor')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        foreach ($roles as $role) {
            if ($user->tokenCan("role:{$role}")) {
                return $next($request);
            }
        }

        abort(403, 'This action is unauthorized.');
    }
}
