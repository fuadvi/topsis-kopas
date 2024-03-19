<?php

namespace App\Http\Middleware;

use App\Http\Traits\RespondFormatter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    use RespondFormatter;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->isAdmin())
        {
            return $next($request);
        }

        return $this->error('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
    }
}
