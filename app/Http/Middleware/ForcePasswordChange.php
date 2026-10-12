<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * New accounts (created by a supervisor) must set their own password
     * before they can use any staff page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->must_change_password) {
            return redirect()->route('profile.edit')
                ->with('must_change', 'Please set a new password before continuing.');
        }

        return $next($request);
    }
}
