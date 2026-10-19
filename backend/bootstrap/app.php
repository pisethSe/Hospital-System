<?php

use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Render's proxy terminates TLS and forwards traffic — trust its
        // headers so request IPs and https detection are correct.
        $middleware->trustProxies(at: '*');

        $middleware->append(\App\Http\Middleware\AddSecurityHeaders::class);

        // This is an API-only application: there is no web "login" route.
        // Returning null prevents Laravel's Authenticate middleware from
        // resolving route('login') for guests (which throws a 500); the
        // AuthenticationException is then rendered as JSON 401 below.
        $middleware->redirectGuestsTo(fn () => null);

        $middleware->alias([
            'role' => EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // API routes must always render JSON errors — e.g. HTTP 401 for
        // unauthenticated requests — even when the client omits the
        // Accept: application/json header. Without this, Laravel tries
        // to redirect guests to a web "login" route that does not exist
        // in this API-only application and returns a 500 instead.
        $exceptions->shouldRenderJsonWhen(
            fn ($request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
