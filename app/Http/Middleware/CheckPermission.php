<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $slug): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if ($user->role === 'super_admin') {
            return $next($request);
        }

        $hasPermission = DB::table('user_permission as up')
            ->join('permissions as p', 'up.permission_id', '=', 'p.id')
            ->where('up.user_id', $user->id)
            ->where('p.slug', $slug)
            ->exists();

        abort_unless($hasPermission, 403, 'Not allowed');

        return $next($request);
    }
}
