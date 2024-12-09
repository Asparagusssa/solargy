<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(StartSession::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $handler = clone $exceptions->handler;

        $exceptions->render(function (Throwable $exception) use ($handler) {
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'message' => 'Not found',
                    'code' => 404
                ], 404);
            }

            return response()->json([
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => (int) $exception->getCode()
            ], 500);
        });
    })->create();
