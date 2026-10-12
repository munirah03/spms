<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'staffonly' => \App\Http\Middleware\StaffOnly::class,
            'supervisoronly' => \App\Http\Middleware\SupervisorOnly::class,
            'passwordchange' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);

        $middleware->appendToGroup('web', \App\Http\Middleware\SecurityHeaders::class);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A file bigger than PHP's post_max_size never reaches our own
        // validation — Laravel throws this before the request is routed.
        // Catch it here and send the customer back to the form with the
        // same friendly red error box used for every other validation error.
        $exceptions->render(function (\Symfony\Component\HttpFoundation\Exception\PostTooLargeException $e, $request) {
            if ($request->routeIs('orders.store')) {
                return redirect()->route('orders.create')
                    ->withErrors(['file' => 'The file cannot be submitted — it is larger than this system allows (max 5MB). Please upload a smaller file.'])
                    ->withInput($request->except('file'));
            }

            return redirect()->back()
                ->withErrors(['file' => 'The uploaded file is too large.']);
        });
    })->create();
