<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupervisorOnly
{
    /**
     * Handle an incoming request.
     *
     * Restricts access to supervisor-level functions: daily/revenue
     * reports and staff account management. Regular counter staff
     * (role = 'staff') are authenticated but not authorised here,
     * even though they pass the general StaffOnly check.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'supervisor') {
            abort(403, 'Unauthorized. Supervisor access only.');
        }

        return $next($request);
    }
}
