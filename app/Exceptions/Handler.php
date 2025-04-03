<?php

namespace App\Exceptions;

use App\Models\GlobalError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {

        $this->renderable(function (Throwable $exception, $request) {
            if ($exception instanceof QueryException && $exception->getCode() === '23503') {

                $validator = \Validator::make([], []);
                $validator->errors()->add('error', 'El registro no puede ser eliminado, verifique que no este siendo utilizado por alguna orden de servicio');
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        });

        $this->reportable(function (Throwable $e) {

            $error = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];

            GlobalError::create(["error" => json_encode($error)]);
        });
    }
}
