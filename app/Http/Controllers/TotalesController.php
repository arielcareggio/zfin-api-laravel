<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Totales;
use App\Models\Personas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TotalesController extends Controller
{

    // SI AGREGA UN MOVIMIENTO, SOLO CALCULAR CON EL MONTO AGREGADO

    /**
     * Actualiza el monto BARRIENDO TODA la tabla movimientos
     */
    public static function recalcularTotal($param)
    {
        $id_persona                 = $param['id_persona'];
        $id_banco_cuenta            = $param['id_banco_cuenta'];
        $id_banco_cuenta_anterior   = $param['id_banco_cuenta_anterior'] ?? null;
        
        $total = [];
        try {
            $persona = Personas::find($id_persona);

            $total['banco_cuenta'] = ['id_banco_cuenta' => $id_banco_cuenta, 'total' => self::setMonto($id_banco_cuenta, $persona->id_cuenta, true)];

            if($id_banco_cuenta_anterior != null){
                $total['banco_cuenta_anterior'] = ['id_banco_cuenta_anterior' => $id_banco_cuenta_anterior, 'total' => self::setMonto($id_banco_cuenta_anterior, $persona->id_cuenta, true)]; 
            }
            return $total;
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el total'], 500);
        }
    }

    /**
     * Actualiza el monto BARRIENDO TODA la tabla movimientos
     */
    public static function recalcularTotalParcial($param)
    {
        $id_persona         = $param['id_persona'];
        $id_banco_cuenta    = $param['id_banco_cuenta'];
        $monto              = $param['monto'];

        $total = [];
        try {

            $persona = Personas::find($id_persona);

            $total['banco_cuenta'] = ['id_banco_cuenta' => $id_banco_cuenta, 'total' => self::setMonto($id_banco_cuenta, $persona->id_cuenta, false, $monto)];

            return $total;

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al actualizar el total'], 500);
        }
    }

    /**
     * Actualiza el total en la tabla 'totales' y si el registro no existe lo inserta
     */
    private static function setMonto($id_banco_cuenta, $id_cuenta, $barridoCompleto, $monto = null)
    {
        $totales = Totales::where('id_cuenta', $id_cuenta)
                                ->where('id_banco_cuenta', $id_banco_cuenta)
                                ->first();

        if($barridoCompleto || !$totales){
            $total = self::getMontoBarridoTotal($id_banco_cuenta);
        }else{
            $total = self::getMontoParcialTotal($totales, $monto);
        }

        if (!$totales) {
            $totales = new Totales();// No existe el calculo, crear una nueva instancia del modelo Totales
        }

        $totales->id_cuenta = $id_cuenta;
        $totales->id_banco_cuenta = $id_banco_cuenta;
        $totales->total = $total;
        $totales->save();

        return $total;
    }

    /**
     * Calcula el total, barriendo TODA la tabla movimiento
     */
    private static function getMontoBarridoTotal($id_banco_cuenta)
    {
        //Obtener todos los movimientos que coincidan con el id_banco_cuenta
        $result = DB::table('movimientos')
        ->select(DB::raw('SUM(monto) as total'))
        ->where('id_banco_cuenta', $id_banco_cuenta)
        ->first();
    
        return $result->total ?? 0;
    }

    /**
     * Calcula el total, SOLO calculo parcial, sin necesidad de barrer toda la tabla movimientos
     */
    private static function getMontoParcialTotal($totales, $monto)
    {
        if (!$totales) {
            return $monto;
        }
        return $totales->total + $monto;
    }
}
