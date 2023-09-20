<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosTipos;
use Illuminate\Support\Facades\Validator;

class MovimientosTiposController extends Controller
{
    public function addMovimientoTipo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'id_cuenta' => 'required|integer',
            'id_tipo' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $movimientoTipo = MovimientosTipos::create([
                'name' => $request->input('name'),
                'id_cuenta' => $request->input('id_cuenta'),
                'id_tipo' => $request->input('id_tipo'),
            ]);

            return response()->json(['message' => 'Tipo Movimiento registrado correctamente', 'movimientoTipo' => $movimientoTipo], 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateMovimientoTipo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento_tipo' => 'required|integer',
            'name' => 'required|string',
            'id_tipo' => 'required|integer',
            'id_cuenta' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $movimientoTipo = MovimientosTipos::find($request->input('id_movimiento_tipo'));

            if (!$movimientoTipo) {
                return response()->json(['error' => 'El Tipo Movimiento no fue encontrado'], 404);
            }

            $movimientoTipo->name = $request->input('name');
            $movimientoTipo->id_tipo = $request->input('id_tipo');
            $movimientoTipo->id_cuenta = $request->input('id_cuenta');
            $movimientoTipo->save();

            return response()->json(['message' => 'Tipo Movimiento actualizado correctamente', 'movimientoTipo' => $movimientoTipo], 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deleteMovimientoTipo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento_tipo' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        try {
            $movimientoTipo = MovimientosTipos::find($request->input('id_movimiento_tipo'));
    
            if (!$movimientoTipo) {
                return response()->json(['error' => 'El Tipo Movimiento no fue encontrado'], 404);
            }
    
            $movimientoTipo->delete();

            return response()->json(['message' => 'Tipo Movimiento eliminado correctamente'], 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
