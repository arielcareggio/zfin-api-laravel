<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\LogController;

class CaptureResponse
{
    /**
     * Captura la entrada y la salida, de esta manera lo primero y ultimo que se ejecutara es este metodo
     */
    public function handle($request, Closure $next)
    {
        //LogController::addLog('Entrada', null, $request, 0);
        $response = $next($request);

        // Capturar la respuesta
        $responseContent = $response->getContent();
        //$responseHeaders = $response->headers->all();
        $responseStatusCode = $response->getStatusCode();

        // Verificar si hay un error o excepción en la respuesta
        /* if ($responseStatusCode >= 400 && $responseStatusCode <= 599) {
            LogController::addLog('Error', $responseContent, $request, 1, $responseStatusCode);
            return $response;
        } */

        LogController::addLog('Salida', $responseContent, $request, 0, $responseStatusCode);
        return $response;
    }
}