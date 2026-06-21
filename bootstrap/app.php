<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
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
        /*
         * Helper: render an error view with the master layout and shared error bag.
         */
        $errorView = fn (int $status, string $view) => response()->view(
            "errors.{$view}",
            ['errors' => new ViewErrorBag],
            $status
        );

        /* ───── 401 Unauthenticated ───── */
        $exceptions->render(function (AuthenticationException $e, $request) use ($errorView) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 401);
            }
            return redirect()->guest(route('tyro-login.login'));
        });

        /* ───── 403 Forbidden / AccessDenied ───── */
        $exceptions->render(function (AccessDeniedHttpException $e, $request) use ($errorView) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage() ?: 'Forbidden'], 403);
            }
            return $errorView(403, '403');
        });

        /* ───── 404 Not Found ───── */
        $exceptions->render(function (NotFoundHttpException $e, $request) use ($errorView) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not found'], 404);
            }
            return $errorView(404, '404');
        });

        /* ───── 419 Session Expired ───── */
        $exceptions->render(function (TokenMismatchException $e, $request) use ($errorView) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired'], 419);
            }
            return $errorView(419, '419');
        });

        $exceptions->render(function (HttpException $e, $request) use ($errorView) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired'], 419);
                }
                return $errorView(419, '419');
            }

            if ($e->getStatusCode() === 503) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Under maintenance'], 503);
                }
                return $errorView(503, '503');
            }

            if (in_array($e->getStatusCode(), [403, 404, 500], true)) {
                $view = (string) $e->getStatusCode();
                if ($request->expectsJson()) {
                    return response()->json(['message' => $e->getMessage() ?: $view], (int) $view);
                }
                return $errorView((int) $view, $view);
            }
        });

        /* ───── 429 Too Many Requests ───── */
        $exceptions->render(function (ThrottleRequestsException $e, $request) {
            $message = 'Too many requests. Please wait before trying again.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 429);
            }
            return back()->with('error', $message);
        });

        /* ───── Model Not Found (Eloquent) ───── */
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Record not found'], 404);
            }
            return back()->with('error', 'The requested record was not found. It may have been deleted.');
        });

        /* ───── Database Query Errors ───── */
        $exceptions->render(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'A database error occurred.'], 400);
            }

            $sqlState = $e->getPrevious()?->getCode();

            // Table not found - show the 500 error page
            if ($sqlState === '42S02' || str_contains($e->getMessage(), 'Base table or view not found')) {
                return response()->view('errors.500', ['errors' => new ViewErrorBag], 500);
            }

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

            return back()->with('error', 'A database error occurred. Please try again.')->withInput();
        });

        /* ───── Fallback: any other exception ───── */
        $exceptions->render(function (\Throwable $e, $request) use ($errorView) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Server error'], 500);
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }

            return $errorView(500, '500');
        });
    })->create();
