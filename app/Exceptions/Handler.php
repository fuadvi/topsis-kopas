<?php

namespace App\Exceptions;

use App\Http\Traits\RespondFormatter;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    use RespondFormatter;
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
//    public function register(): void
//    {
//        $this->reportable(function (Throwable $e) {
//            //
//        });
//
//        $this->renderable(function (NotFoundHttpException $err, $request) {
//            $message = basename($err->getMessage());
//            $nameModel = str_contains($message, ']') ? strstr($message, ']', true) : $message;
//            $model = preg_replace('/([a-z])([A-Z])/', '$1 $2', $nameModel);
//
//            return $this->error('Not Found.'." {$model}",401);
//        });
//
//        $this->renderable(function (QueryException $err, $request) {
//            if ($request->method() === 'DELETE')
//            {
//                return $this->error("Tidak Bisa Menghapus id ini di karenakan id sudah di gunakan di table lain");
//            }
//
//            return $this->error("Internal Server Error");
//        });
//
//    }

    protected function unauthenticated($request, AuthenticationException $exception) {
        if ($request->expectsJson()) {
            return $this->error('Unauthenticated.',401);
        }

        // For web requests, you can customize the redirect
        return redirect()->guest(route('login'));
    }
}
