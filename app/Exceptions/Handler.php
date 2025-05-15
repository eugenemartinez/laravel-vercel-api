<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request; // Add this
use Illuminate\Validation\ValidationException; // Add this
use Throwable;
use Illuminate\Support\Facades\Log; // For logging the original error

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
            // You can add custom reporting logic here if needed
            // For Vercel, errors will go to stderr if LOG_CHANNEL is set,
            // but explicit logging here can be useful for debugging this specific scenario.
            Log::error("Original Exception: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString() // Be careful with full trace in production logs
            ]);
        });

        // This ensures that for an API (or if the view factory isn't bound),
        // we always try to return a JSON response for exceptions.
        $this->renderable(function (Throwable $e, Request $request) {
            // If the view factory is not bound (which is our case for an API-only app)
            // OR if the request explicitly asks for JSON.
            if (!app()->bound('view') || $request->expectsJson()) {
                // If it's a validation exception, Laravel's default JSON response is good.
                if ($e instanceof ValidationException) {
                    return $e->response ?? $this->prepareJsonResponse($request, $e);
                }
                // For all other exceptions, prepare a JSON response.
                return $this->prepareJsonResponse($request, $e);
            }

            // Fallback for non-API requests if views were somehow expected
            // For a pure API, you might even remove this or force JSON always.
            // However, the !app()->bound('view') should catch our primary case.
            // If we reach here in an API context, it's unexpected.
            // Let's ensure it still returns JSON to avoid the view error.
            if ($request->is('api/*') || $request->expectsJson() || !app()->bound('view')) {
                 return $this->prepareJsonResponse($request, $e);
            }

            // Default behavior if none of the above (should ideally not be reached in our API setup)
            // return parent::render($request, $e); // This might try to render a view
        });
    }
}
