<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckAccountApproved;
use App\Http\Middleware\CheckAccountStatus;
use App\Http\Middleware\CheckTrialAccess;
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
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        
        $middleware->alias([
            'role' => CheckRole::class,
            'approved' => CheckAccountApproved::class,
            'trial' => CheckTrialAccess::class,
            'account.status' => CheckAccountStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
