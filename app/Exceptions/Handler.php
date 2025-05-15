<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error("Original Exception: " . $e->getMessage(), [
                'exception_class' => get_class($e), // Log the class of the exception
                // 'trace' => $e->getTraceAsString() // Full trace can be very long
            ]);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            // If the view factory is not bound OR if the request explicitly asks for JSON.
            if (!app()->bound('view') || $request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return $e->response ?? $this->prepareJsonResponse($request, $e);
                }
                return $this->prepareJsonResponse($request, $e);
            }

            // Fallback to ensure JSON if other conditions are met (largely covered by the above)
            // This second 'if' block might be redundant if the first one is comprehensive enough.
            // Consider simplifying if the first block handles all intended API error scenarios.
            if ($request->is('api/*') || !app()->bound('view')) {
                 return $this->prepareJsonResponse($request, $e);
            }

            // If we reach here, it means views are bound, JSON wasn't expected,
            // and it's not an API route. Let the parent handle it (might render HTML).
            // For a pure API, you might never want to reach parent::render.
            // return parent::render($request, $e); // This line is currently commented out in your provided file
        });
    }

    /**
     * Register the error view paths.
     * Override to prevent issues if the 'view' service is not bound.
     *
     * @return void
     */
    protected function registerErrorViewPaths()
    {
        // Only attempt to register error view paths if the view service is actually available.
        // For a pure API where 'view' might not be bound, this prevents an error.
        if (app()->bound('view')) {
            parent::registerErrorViewPaths();
        }
    }
}
