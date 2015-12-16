<?php

namespace OctaneSociety\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \OctaneSociety\Http\Middleware\ProvideCompiledAssets::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \OctaneSociety\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
       // \OctaneSociety\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \OctaneSociety\Http\Middleware\Authenticate::class,
        'guest' => \OctaneSociety\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}
