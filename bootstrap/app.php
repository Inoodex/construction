<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 401);
            }
            return redirect()->guest(route('tyro-login.login'));
        });

        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired'], 419);
                }
                return redirect()->guest(route('tyro-login.login'))
                    ->with('error', 'Your session has expired. Please log in again.');
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not found'], 404);
            }
            return response()->view('errors.404', ['errors' => new ViewErrorBag], 404);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage() ?: 'Forbidden'], 403);
            }
            return back()->with('error', $e->getMessage() ?: 'You are not authorized to perform this action.')
                ->withInput();
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Record not found'], 404);
            }
            return back()->with('error', 'The requested record was not found. It may have been deleted.');
        });

        $exceptions->render(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'A database error occurred.'], 400);
            }

            $sqlState = $e->getPrevious()?->getCode();

            // Integrity constraint violation (foreign key)
            if ($sqlState === 23000 || str_contains($e->getMessage(), 'Integrity constraint violation')) {
                $message = 'Cannot delete this record because it has related records linked to it. Remove or reassign them first.';

                if (preg_match('/constraint fails \(`[^`]+`\.`[^`]+`, CONSTRAINT `[^`]+` FOREIGN KEY.*?REFERENCES `([^`]+)`/', $e->getMessage(), $m)) {
                    $parentTable = str_replace('_', ' ', $m[1]);
                    $message = "Cannot delete this record because it has related {$parentTable} records. Remove or reassign them first.";
                }

                return back()->with('error', $message)->withInput();
            }

            // Duplicate entry
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', 'A record with this value already exists. Please use a different value.')->withInput();
            }

            if (config('app.debug')) {
                throw $e;
            }

            return back()->with('error', 'A database error occurred. Please try again.')->withInput();
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Server error'], 500);
            }

            // Let ValidationException use default redirect behaviour
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }

            if (config('app.debug')) {
                throw $e;
            }

            return back()->with('error', 'An unexpected error occurred. Please try again.');
        });
    })->create();
