<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return redirect()->route('notauth');
        });
        $this->renderable(function ($request, Exception $exception) {
            if ($this->isHttpException($exception)) {
                if ($exception->getStatusCode() == 500) {
                    return response()->view('errors.' . '500', ['exception' => $e], 500);
                }
            }
            return parent::render($request, $exception);
        });
        $this->renderable(function ($request, Exception $exception) {
            if ($this->isHttpException($exception)) {
                if ($exception->getStatusCode() == 403) {
                    return response()->view('errors.' . '403', ['exception' => $e], 403);
                }
            }
            return parent::render($request, $exception);
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return redirect()->back()->with('error', 'Requested record not found / deleted!');
        }
        if ($e instanceof ErrorException) {
            return response()->view('errors.error', ['exception' => $e]);
        }
        return parent::render($request, $e);
    }
}
