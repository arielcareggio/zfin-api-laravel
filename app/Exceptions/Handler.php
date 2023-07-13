<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Http\Controllers\LogController;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            LogController::addLog('Acceso no autorizado - Posible token invalido', json_encode(['Archivo' => $exception->getFile(), 'Linea' => $exception->getLine(), 'Exception'=> $exception->getMessage()]), $request, 1, 401);
            return response()->json(['error' => 'Acceso no autorizado'], 401);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            LogController::addLog('El método HTTP no está permitido para esta ruta.', json_encode(['Archivo' => $exception->getFile(), 'Linea' => $exception->getLine(), 'Exception'=> $exception->getMessage()]), $request, 1, 405);
            return response()->json(['error' => 'El método HTTP no está permitido para esta ruta.'], 405);
        }

        //cada vez que ocurra una excepción en la aplicación DONDE no se capture con un try-catch, Laravel capturará la excepción y pasara por el siguiente if, 
        //donde puedo manejar el error a gusto, como guardar LOGS.
        if ($exception instanceof \Exception) {
            LogController::addLog('Error general - Exception NO capturada por Try-Catch', json_encode(['Archivo' => $exception->getFile(), 'Linea' => $exception->getLine(), 'Exception'=> $exception->getMessage()]), $request, 1, 405);
            return response()->json(['error' => 'Error general'], 405);
        } 

        return parent::render($request, $exception);
    }
}
