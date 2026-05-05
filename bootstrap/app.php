<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsCustomer;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'customer' => EnsureUserIsCustomer::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/moolre',
            'webhooks/transflow',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TransportException $e, Request $request) {
            Log::error('Mail transport failure', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'user' => $request->user()?->email,
            ]);

            $userMessage = str_contains($e->getMessage(), 'unrecognised IP')
                ? 'Our email service is temporarily unavailable from this location. Please try again later or contact us directly.'
                : 'We could not send the email right now. Please try again in a few moments.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $userMessage], 503);
            }

            return back()->withErrors(['email' => $userMessage]);
        });
    })->create();
