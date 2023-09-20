<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Http\Controllers\LogController;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * Para manejar las Excepciones que lance Laravel
     */
    public function render($request, Throwable $exception)
    {
        //creamos un arreglo asociativo donde las claves son los tipos de excepción y los valores son las respuestas y mensajes de error correspondientes. 
        $array_exceptionHandlers = [
            UnauthorizedHttpException::class => [
                'message' => 'La sesión ha finalizado, debe iniciar sesión nuevamente.',
                'status_code' => 401, // 401 = Unauthorized
            ],
            MethodNotAllowedHttpException::class => [
                'message' => 'El método HTTP no está permitido para esta ruta.',
                'status_code' => 405,
            ],
            NotFoundHttpException::class => [
                'message' => 'La url solicitada no existe',
                'status_code' => 405,
            ],

            //cada vez que ocurra una excepción en la aplicación DONDE no se capture con un try-catch, Laravel capturará la excepción y pasara por el siguiente if, donde puedo manejar el error a gusto, como guardar LOGS.
            \Exception::class => [
                'message' => 'Error general',
                'status_code' => 405,
            ],
        ];

        //Verificamos si la excepción está en el arreglo y, si es así, genera la respuesta y el mensaje de error correspondientes.
        foreach ($array_exceptionHandlers as $exceptionType => $handler) {
            if ($exception instanceof $exceptionType) {
                $log_id = LogController::addLog($handler['message'], json_encode([
                    'Archivo' => $exception->getFile(),
                    'Linea' => $exception->getLine(),
                    'Exception' => $exception->getMessage(),
                ]), $request, 1, $handler['status_code']);

                return response()->json(['error' => $handler['message'], 'log_id' => $log_id], $handler['status_code']);
            }
        }

        return parent::render($request, $exception);
    }
}
