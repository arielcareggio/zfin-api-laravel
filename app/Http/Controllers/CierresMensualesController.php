<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CierresMensuales;
use App\Models\Movimientos;
use Illuminate\Support\Facades\Validator;

class CierresMensualesController extends Controller
{

    // SI AGREGA UN MOVIMIENTO, SOLO CALCULAR CON EL MONTO AGREGADO
    // SI ACTUALIZA EL MOVIMIENTO, ENVIAR EL MONTO_ACTUAL Y MONTO_NUEVO

    /**
     * Actualiza el monto rapidamente
     */
    public function updateRapido($id_cuenta, $id_banco_cuenta, $monto_actual, $monto_nuevo, $fecha)
    {
        $validator = Validator::make($request->all(), [
            'id_cuenta' => 'required|integer', //Buscar totos los id_personas (7 y 8) que sean de ese id_cuenta (3)
            'id_banco_cuenta' => 'required|integer',
            'fecha' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $movimientos = Movimientos::find($request->input('id_banco_cuenta'));

            if (!$movimientos) {
                return response()->json(['error' => 'La Cuenta del banco no fue encontrada'], 404);
            }

            $movimientos->name = $request->input('name');
            $movimientos->id_banco = $request->input('id_banco');
            $movimientos->nro_cuenta = $request->input('nro_cuenta');
            $movimientos->save();

            return response()->json(['message' => 'Cuenta del banco actualizada correctamente', 'banco' => $movimientos], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el registro'], 500);
        }
    }

    public function addBancoCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'id_banco' => 'required|integer',
            'nro_cuenta' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $bancoCuenta = BancosCuentas::create([
                'name' => $request->input('name'),
                'id_banco' => $request->input('id_banco'),
                'nro_cuenta' => $request->input('nro_cuenta'),
            ]);

            return response()->json(['message' => 'Cuenta del banco registrada correctamente', 'bancoCuenta' => $bancoCuenta], 201);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }
}
