<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimientos;
use Illuminate\Support\Facades\Validator;

class MovimientosController extends Controller
{
    public function getMovimientos(Request $request)
    {
        //->where('c.id_user', request()->user()->id);
        $validator = Validator::make($request->all(), [
            'id_cuenta' => 'nullable|integer', // Para que ni bien se inicia la web obtener todos los movimientos de una cuenta (Cuenta = Personal, AgroRural, etc)
            'id_banco_cuenta' => 'nullable|integer',
            'id_persona' => 'nullable|integer',
            'id_movimiento_tipo' => 'nullable|integer', //Luz / Agua, etc
            'id_tipo' => 'nullable|integer', //Ingreso / egreso
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'monto_desde' => 'nullable|numeric|regex:/^-?\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
            'monto_hasta' => 'nullable|numeric|regex:/^-?\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //throw new \Exception('División por cero no permitida');
        return Movimientos::getMovimientos($request);
    }

    public function addMovimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'id_tipo' => 'required|integer',
            'id_movimiento_tipo' => 'required|integer',
            'id_banco_cuenta' => 'required|integer',
            'id_persona' => 'required|integer',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|regex:/^-?\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
            'url_archivo' => 'nullable|string', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //throw new \Exception('División por cero no permitida');
        try {
            $movimiento = Movimientos::create([
                // 'id_tipo' => $request->input('id_tipo'),
                'id_movimiento_tipo' => $request->input('id_movimiento_tipo'),
                'id_banco_cuenta' => $request->input('id_banco_cuenta'),
                'id_persona' => $request->input('id_persona'),
                'fecha' => $request->input('fecha'),
                'monto' => $request->input('monto'),
                'url_archivo' => $request->input('url_archivo'),
            ]);

            //Calculo TOTAL parcial
            $param['id_persona']        = $request->input('id_persona');
            $param['id_banco_cuenta']   = $request->input('id_banco_cuenta');
            $param['monto']             = $request->input('monto');
            $totales = TotalesController::recalcularTotalParcial($param);

            MovimientosMasUtilizadosController::categoriaMasUtilizada($movimiento);

            return response()->json(['message' => 'Movimiento registrado correctamente', 'movimiento' => $movimiento, 'totales' => $totales], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }

    public function updateMovimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_movimiento' => 'required|integer',
            //'id_tipo' => 'required|integer',
            'id_movimiento_tipo' => 'required|integer',
            'id_banco_cuenta' => 'required|integer',
            'id_persona' => 'required|integer',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|regex:/^-?\d+(\.\d{1,2})?$/', //aceptará valores numéricos con un máximo de dos decimales.
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

            $movimiento_anterior = clone $movimiento;

            //$movimiento->id_tipo = $request->input('id_tipo');
            $movimiento->id_movimiento_tipo = $request->input('id_movimiento_tipo');
            $movimiento->id_banco_cuenta = $request->input('id_banco_cuenta');
            $movimiento->id_persona = $request->input('id_persona');
            $movimiento->fecha = $request->input('fecha');
            $movimiento->monto = $request->input('monto');
            $movimiento->url_archivo = $request->input('url_archivo');
            $movimiento->save();

            // Para un movimiento no es posible modificar la cuenta, ya que las cuentas bancarias no estarían en la otra cuenta, ya que cada cuenta tiene sus propias cuentas bancarias

            //Con el id_persona -> obtengo -> id_cuenta
            $totales = null;

            if (
                $movimiento_anterior->monto != $request->input('monto') ||
                $movimiento_anterior->id_movimiento_tipo != $request->input('id_movimiento_tipo') ||
                $movimiento_anterior->id_banco_cuenta != $request->input('id_banco_cuenta')
            ) {
                //uno es distinto - CALCULO COMPLETO
                $param['id_persona']        = $request->input('id_persona');
                $param['id_banco_cuenta']   = $request->input('id_banco_cuenta');

                if ($movimiento_anterior->id_banco_cuenta != $request->input('id_banco_cuenta')) {
                    $param['id_banco_cuenta_anterior'] = $movimiento_anterior->id_banco_cuenta;
                }
                $totales = TotalesController::recalcularTotal($param);

                if ($movimiento_anterior->id_movimiento_tipo != $request->input('id_movimiento_tipo')) {
                    MovimientosMasUtilizadosController::categoriaMasUtilizada($movimiento, $movimiento_anterior);
                }
            }

            return response()->json(['message' => 'Movimiento actualizado correctamente', 'movimiento' => $movimiento, 'totales' => $totales], 200);
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
$id= $request->input('id_movimiento');
        try {
            $movimiento = Movimientos::find($request->input('id_movimiento')+50);

            if (!$movimiento) {
                return response()->json(['error' => 'El Movimiento no fue encontrado'], 200);
            }

            $movimiento->delete();

            //Recalculamos
            $param['id_movimiento_tipo']    = $movimiento->id_movimiento_tipo;
            $param['id_banco_cuenta']       = $movimiento->id_banco_cuenta;
            $param['id_persona']            = $movimiento->id_persona;
            
            $totales = TotalesController::recalcularTotal($param);
            
            return response()->json(['message' => 'Movimiento eliminado correctamente', 'totales' => $totales], 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al eliminar el registro'], 500);
        }
    }
}
