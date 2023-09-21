<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosAccesosDirectos;
use Illuminate\Support\Facades\Validator;

class MovimientosAccesosDirectosController extends Controller
{
    public function getMovimientosAccesosDirectos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'id_user' => 'required|integer', //obligatorio
            'id_tipo' => 'nullable|string', //opcional
            'id_movimiento_tipo' => 'nullable|string', //opcional
            'id_banco_cuenta' => 'nullable|string', //opcional
            'id_persona' => 'nullable|string', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        return MovimientosAccesosDirectos::getMovimientosAccesosDirectos($request);
    }

    public function addMovimientoAccesoDirecto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tipo' => 'required|integer',
            'id_movimiento_tipo' => 'required|integer',
            'id_banco_cuenta' => 'required|integer',
            'id_persona' => 'required|integer',
            'name' => 'required|string',
            'monto' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
            'url_archivo' => 'nullable|string', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $accesoDirecto = MovimientosAccesosDirectos::create([
                'id_tipo' => $request->input('id_tipo'),
                'id_movimiento_tipo' => $request->input('id_movimiento_tipo'),
                'id_banco_cuenta' => $request->input('id_banco_cuenta'),
                'id_persona' => $request->input('id_persona'),
                'name' => $request->input('name'),
                'monto' => $request->input('monto'),
                'url_archivo' => $request->input('url_archivo'),
            ]);

            return response()->json(['message' => 'Acceso Directo registrado correctamente', 'accesoDirecto' => $accesoDirecto], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateMovimientoAccesoDirecto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento_acceso_directo' => 'required|integer',
            'id_tipo' => 'required|integer',
            'id_movimiento_tipo' => 'required|integer',
            'id_banco_cuenta' => 'required|integer',
            'id_persona' => 'required|integer',
            'name' => 'required|string',
            'monto' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
            'url_archivo' => 'nullable|string', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $accesoDirecto = MovimientosAccesosDirectos::find($request->input('id_movimiento_acceso_directo'));

            if (!$accesoDirecto) {
                return response()->json(['error' => 'El Acceso Directo no fue encontrado'], 404);
            }

            $accesoDirecto->id_tipo = $request->input('id_tipo');
            $accesoDirecto->id_movimiento_tipo = $request->input('id_movimiento_tipo');
            $accesoDirecto->id_banco_cuenta = $request->input('id_banco_cuenta');
            $accesoDirecto->id_persona = $request->input('id_persona');
            $accesoDirecto->name = $request->input('name');
            $accesoDirecto->monto = $request->input('monto');
            $accesoDirecto->url_archivo = $request->input('url_archivo');
            $accesoDirecto->save();

            return response()->json(['message' => 'Acceso Directo actualizado correctamente', 'accesoDirecto' => $accesoDirecto], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deleteMovimientoAccesoDirecto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento_acceso_directo' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $accesoDirecto = MovimientosAccesosDirectos::find($request->input('id_movimiento_acceso_directo'));

            if (!$accesoDirecto) {
                return response()->json(['error' => 'El Acceso Directo no fue encontrado'], 404);
            }

            $accesoDirecto->delete();

            return response()->json(['message' => 'Acceso Directo eliminado correctamente'], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
