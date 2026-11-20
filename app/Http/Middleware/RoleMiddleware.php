<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;  

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
// app/Http/Middleware/RoleMiddleware.php
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (! in_array($request->user()?->role, $roles)) {
        abort(403, 'Anda tidak memiliki hak akses.');
    }
    return $next($request);
}
}