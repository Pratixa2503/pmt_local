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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'password.updated'     => \App\Http\Middleware\CheckPasswordUpdated::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
        $middleware->appendToGroup('web', \App\Http\Middleware\PreventBackHistory::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckPasswordUpdated::class);
    })
    ->withProviders([
        \App\Providers\BroadcastServiceProvider::class, // ✅ fully-qualified
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
