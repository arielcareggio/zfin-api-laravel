<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientosMasUtilizados;
use App\Models\Cuentas;
use App\Models\Personas;

class MovimientosMasUtilizadosController extends Controller
{
    static public function categoriaMasUtilizada($movimiento, $movimientoAnterior = null){
        self::update($movimiento, 1);
        
        if($movimientoAnterior != null){
            self::update($movimientoAnterior, -1);
        }
    }

    /**
     * Hace el trabajo de incrementar o decrementar la cantidad de veces que se utiliza el id_movimiento_tipo espoecifico
     */
    static private function update($movimiento, $cantidad_a_sumar){
        $persona = Personas::find($movimiento->id_persona);
        if ($persona) {
            // se utiliza el método firstOrCreate para buscar el registro existente o crear uno nuevo si no se encuentra.
            $totales = MovimientosMasUtilizados::firstOrCreate(
                ['id_cuenta' => $persona->id_cuenta, 'id_movimiento_tipo' => $movimiento->id_movimiento_tipo],
            );
            //se utiliza el método increment para aumentar en 1 el valor del campo "cantidad" en el registro existente.
            $totales->increment('cantidad', $cantidad_a_sumar);
        }else{
            //GUARDAR EN LOG QUE NO SE ENCONTRO LA PERSONA, PERO COMO NO ES ESCENCIAL NO CORTAL EL FLUJO, QUE EL USER NO SE ENTERE
        }
    }
}
