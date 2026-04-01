<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\InvoiceOwnerMiddleware;
use App\Http\Middleware\ValidatePollToken;
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
            'admin' => AdminMiddleware::class,
            'invoice.owner' => InvoiceOwnerMiddleware::class,
            'poll.token' => ValidatePollToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
