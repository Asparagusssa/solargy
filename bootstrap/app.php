<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
            $code = 500;
            $message = 'Неизвестная ошибка';

            if ($exception instanceof ModelNotFoundException) {
                $code = 404;
                $message = 'Not found';
            } elseif ($exception instanceof ValidationException) {
                $code = 422;
                $message = $exception->validator->errors()->all();
            } elseif ($exception instanceof AuthorizationException) {
                $code = 403;
                $message = 'Forbidden: ' . ($exception->getMessage() ?: 'Access denied.');
            } elseif ($exception instanceof AuthenticationException) {
                $code = 401;
                $message = 'Unauthorized: Authentication required.';
            } elseif ($exception instanceof HttpException) {
                $code = $exception->getStatusCode();
                $message = $exception->getMessage();
            } else {
                $message = $exception->getMessage() ?: 'An unexpected error occurred.';
            }

            return response()->json([
                'error' => true,
                'message' => $message,
                'code' => $code,
                'type' => class_basename($exception),
            ], $code);
        });
    })->create();
