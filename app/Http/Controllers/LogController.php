<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    static public function addLog($mensaje, $json_salida, Request $request, $is_error, $html_code = 0)
    {
        $ip = self::getIP();

        $json_entrada = json_encode(request()->all());
        if (self::isLogin()) {
            $json_entrada = self::ocultarPassword($json_entrada);
        }
        try {
            $log = Log::create([
                'ip' => $ip,
                'id_user' => request()->user()->id ?? null,
                'metodo' => request()->fullUrl(),
                'is_error' => $is_error,
                'html_code' => $html_code,
                'mensaje' => $mensaje,
                'json_entrada' => $json_entrada,
                'json_salida' => $json_salida == '' ? null : $json_salida,
                'headers' => json_encode($request->headers->all()) //$headers == null ? null : json_encode($headers)
            ]);
            return $log->id; // Devolver el ID del registro creado
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    // Dentro de tu controlador o en cualquier lugar donde tengas acceso a la instancia de Request
    static private function getIP()
    {
        try {
            return request()->getClientIp();
        } catch (\Exception $e) {
        }
        return null;
    }

    // Dentro de tu controlador o en cualquier lugar donde tengas acceso a la instancia de Request
    static private function isLogin()
    {
        try {
            if (strpos(request()->fullUrl(), '/api/login')) {
                return true;
            }
        } catch (\Exception $e) {
        }
        return false;
    }

    // Dentro de tu controlador o en cualquier lugar donde tengas acceso a la instancia de Request
    static private function ocultarPassword($json_entrada)
    {
        try {
            // Decodificar el JSON en un array asociativo
            $data = json_decode($json_entrada, true);

            // Ocultar el valor del campo "password"
            $data['password'] = '*****';

            // Codificar el array modificado en formato JSON
            $hiddenPasswordJson = json_encode($data);

            return $hiddenPasswordJson;
        } catch (\Exception $e) {
            return 'No se pudo ocultar el campo password: ' . $e->getMessage();
        }
        return 'No se pudo ocultar el campo password';
    }
}
