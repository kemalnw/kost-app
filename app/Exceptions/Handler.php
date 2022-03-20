<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'The requested resource was not found.',
                ], $e->getStatusCode());
            }
        });

        $this->renderable(function (InsufficientFundsException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Insufficient funds, please top up your balance.',
                ], $e->getStatusCode());
            }
        });

        $this->renderable(function (RoomNotAvailableException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Room not available.',
                ], $e->getStatusCode());
            }
        });
    }

    /**
     * Determine if the exception handler response should be JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return bool
     */
    protected function shouldReturnJson($request, Throwable $e)
    {
        return $request->is('api/*');
    }
}
