<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\AdminAuthentication;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        // $middleware->statefulApi();

        $middleware->encryptCookies(except: [
            'cookie_name',
        ]);

        $middleware->validateCsrfTokens(except: [
            '/telescope/*'
        ]);

        $middleware->alias([
            'admin.auth' => AdminAuthentication::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $exception, Request $request) {
            $handler = new ApiException();
            return $handler->handle($request, $exception);
        });
    })->create();
