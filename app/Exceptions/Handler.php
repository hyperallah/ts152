<?php

namespace App\Exceptions;

use Aws\S3\Exception\S3Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use League\Flysystem\UnableToWriteFile;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        if($request->is('api/*') || $this->isHttpException($e)) {
            if($e instanceof UnableToWriteFile && $e->getPrevious() instanceof S3Exception) {
                return response()->json(["err_message:" => "{$e->getPrevious()->getMessage()}"], 503);
            }

            if($e instanceof UnableToWriteFile) {
                return response()->json(["err_message:" => "{$e->location()}", "Reason: {$e->reason()}"], 503);
            }
        }

        return parent::render($request, $e);
    }
}
