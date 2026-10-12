<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
            'keranjang/*',
            'keranjang',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sesi telah berakhir. Silakan muat ulang halaman.',
                    'csrf_expired' => true,
                ], 419);
            }

            return redirect()->back()->withInput()->with('warning', 'Sesi halaman telah kedaluwarsa. Halaman telah diperbarui, silakan coba lagi.');
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, Request $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'Sesi telah berakhir. Silakan muat ulang halaman.',
                        'csrf_expired' => true,
                    ], 419);
                }

                return redirect()->back()->withInput()->with('warning', 'Sesi halaman telah kedaluwarsa. Halaman telah diperbarui, silakan coba lagi.');
            }
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
