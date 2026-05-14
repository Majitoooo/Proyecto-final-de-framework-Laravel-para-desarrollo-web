<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response
    {
        // No autenticado
        if (!auth()->check()) {

            return redirect('/login');
        }

        // No es admin
        if (auth()->user()->role !== 'admin') {

            abort(403);
        }

        return $next($request);
    }
}