<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimientos;
use Illuminate\Support\Facades\Validator;

class MovimientosController extends Controller
{
    public function addMovimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento_tipo' => 'required|integer',
            'id_banco_cuenta' => 'required|integer',
            'id_persona' => 'required|integer',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
            'url_archivo' => 'nullable|string', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $movimiento = Movimientos::create([
                'id_movimiento_tipo' => $request->input('id_movimiento_tipo'),
                'id_banco_cuenta' => $request->input('id_banco_cuenta'),
                'id_persona' => $request->input('id_persona'),
                'fecha' => $request->input('fecha'),
                'monto' => $request->input('monto'),
                'url_archivo' => $request->input('url_archivo'),
            ]);

            return response()->json(['message' => 'Movimiento registrado correctamente', 'movimiento' => $movimiento], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateMovimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento' => 'required|integer',
            'id_movimiento_tipo' => 'required|integer',
            'id_banco_cuenta' => 'required|integer',
            'id_persona' => 'required|integer',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
            'url_archivo' => 'nullable|string', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $movimiento = Movimientos::find($request->input('id_movimiento'));

            if (!$movimiento) {
                return response()->json(['error' => 'El Movimiento no fue encontrado'], 404);
            }

            $movimiento_anterior = $movimiento;

            $movimiento->id_movimiento_tipo = $request->input('id_movimiento_tipo');
            $movimiento->id_banco_cuenta = $request->input('id_banco_cuenta');
            $movimiento->id_persona = $request->input('id_persona');
            $movimiento->fecha = $request->input('fecha');
            $movimiento->monto = $request->input('monto');
            $movimiento->url_archivo = $request->input('url_archivo');
            $movimiento->save();

            //hacer validaciones

            // id_movimiento_tipo_actual = id_movimiento_tipo_nuevo
            // id_banco_cuenta_actual = id_banco_cuenta_nuevo
            // id_persona_actual = id_persona_nueva
            // fecha_actual = fecha_nueva
            // monto_actual = monto_nueva
            if($movimiento_anterior->id_movimiento_tipo != $request->input('id_movimiento_tipo') ||
                $movimiento_anterior->id_banco_cuenta != $request->input('id_banco_cuenta') ||
                $movimiento_anterior->id_persona != $request->input('id_persona') ||
                $movimiento_anterior->fecha != $request->input('fecha') ||
                $movimiento_anterior->monto != $request->input('monto')){
                    //uno es distinto
                    
                }



            return response()->json(['message' => 'Movimiento actualizado correctamente', 'movimiento' => $movimiento], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function deleteMovimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        try {
            $movimiento = Movimientos::find($request->input('id_movimiento'));
    
            if (!$movimiento) {
                return response()->json(['error' => 'El Movimiento no fue encontrado'], 404);
            }
    
            $movimiento->delete();

            return response()->json(['message' => 'Movimiento eliminado correctamente'], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
