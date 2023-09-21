<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Totales extends Model
{
    use HasFactory;

    protected $fillable = ['id_cuenta', 'id_banco_cuenta', 'total'];

    protected $primaryKey = 'id';

    protected $table = 'totales';

    static public function getTotales($request){
        try {
            $query = Totales::from('totales as t')
                ->select(
                    't.*',
                    'bc.name as nombre_banco_cuenta',
                    'b.name as nombre_banco',
                    'c.name as nombre_cuenta'
                )
                ->join('bancos_cuentas as bc', 'bc.id', '=', 't.id_banco_cuenta')
                ->join('bancos as b', 'b.id', '=', 'bc.id_banco')
                ->join('cuentas as c', 'c.id', '=', 't.id_cuenta')
                ->where('c.id_user', request()->user()->id)
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función

                ->when($request->input('id_cuenta'), function ($query, $id_cuenta) {
                    return $query->where('t.id_cuenta', $id_cuenta);
                })
                ->when($request->input('id_banco_cuenta'), function ($query, $id_banco_cuenta) {
                    return $query->where('t.id_banco_cuenta', $id_banco_cuenta);
                })
                ->when($request->input('total_desde'), function ($query, $total_desde) {
                    return $query->where('t.total', '>=', $total_desde);
                })
                ->when($request->input('total_hasta'), function ($query, $total_hasta) {
                    return $query->where('t.total', '<=', $total_hasta);
                })
                ->when($request->input('name_cuenta'), function ($query, $name_cuenta) {
                    return $query->where('c.name', 'like', '%' . $name_cuenta . '%');
                })
                ->when($request->input('name_banco_cuenta'), function ($query, $name_banco_cuenta) {
                    return $query->where('bc.name', 'like', '%' . $name_banco_cuenta . '%');
                });

            $totales = $query->orderByDesc('t.id')->get();

            return response()->json($totales, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener los totales de cuentas'], 500);
        }
    }
    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * Bancos también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function cuenta()
    {
        return $this->belongsTo(Cuentas::class, 'id_cuenta');
    }

    public function bancosCuentas()
    {
        return $this->belongsTo(BancosCuentas::class, 'id_banco_cuenta');
    }
}
