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
        $response = $next($request);
        $responseContent = $response->getContent(); //Captura la respuesta
        //$responseHeaders = $response->headers->all();

        $isSuccessful = true;
        $array_nombre = 'data';
        $contenido_respuesta = $response->getData();

        //es un error?
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            $array_nombre = 'error';
            if(isset($response->getData()->error)){
                $contenido_respuesta = $response->getData()->error;
            }
        }
        
        $log_id = 0;
        if(isset($response->getData()->log_id)){
            $log_id = $response->getData()->log_id;
        }

        LogController::addLog('Salida', $responseContent, $request, 0, $response->getStatusCode()); //Inserta en la tabla log

        //Es lo que devolvemos al panel
        return response()->json(
            [
                'success'=>$isSuccessful, 
                'status' => $response->getStatusCode(),
                'logId' => $log_id,
                $array_nombre => $contenido_respuesta
            ], 
            $response->getStatusCode()
        );
    }
}