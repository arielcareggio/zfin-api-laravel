<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    use HasFactory;

    protected $fillable = ['id_movimiento_tipo', 'id_banco_cuenta', 'id_persona', 'fecha', 'monto', 'url_archivo'];

    protected $primaryKey = 'id';

    protected $table = 'movimientos';

    /**
     * Retorna los movimientos
     */
    static public function getMovimientos($request)
    {
        try {
            $query = Movimientos::from('movimientos as m')
                ->select(
                    'm.*',
                    'mt.name as name_movimiento_tipo',
                    'bc.name as name_banco_cuenta',
                    'b.name as name_banco',
                    'p.name as name_persona'
                )
                ->join('bancos_cuentas as bc', 'bc.id', '=', 'm.id_banco_cuenta')
                ->join('bancos as b', 'b.id', '=', 'bc.id_banco')
                ->join('cuentas as c', 'c.id', '=', 'b.id_cuenta')
                ->join('movimientos_tipos as mt', 'mt.id', '=', 'm.id_movimiento_tipo')
                ->join('personas as p', 'p.id', '=', 'm.id_persona')
                ->where('c.id_user', request()->user()->id)
                ->where('c.id', $request->input('id_cuenta'))
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('id_movimiento_tipo'), function ($query, $id_movimiento_tipo) {
                    return $query->where('ma.id_movimiento_tipo', $id_movimiento_tipo);
                })
                ->when($request->input('id_banco_cuenta'), function ($query, $id_banco_cuenta) {
                    return $query->where('m.id_banco_cuenta', $id_banco_cuenta);
                })
                ->when($request->input('id_persona'), function ($query, $id_persona) {
                    return $query->where('m.id_persona', $id_persona);
                })
                ->when($request->input('id_tipo'), function ($query, $id_tipo) {
                    return $query->join('movimientos_tipos as mt', 'mt.id', '=', 'm.id_movimiento_tipo')
                        ->where('mt.id_tipo', $id_tipo);
                })
                ->when($request->input('fecha_desde'), function ($query, $fecha_desde) {
                    return $query->where('m.fecha', '>=', $fecha_desde);
                })
                ->when($request->input('fecha_hasta'), function ($query, $fecha_hasta) {
                    return $query->where('m.fecha', '<=', $fecha_hasta);
                })
                ->when($request->input('monto_desde'), function ($query, $monto_desde) {
                    return $query->where('m.monto', '>=', $monto_desde);
                })
                ->when($request->input('monto_hasta'), function ($query, $monto_hasta) {
                    return $query->where('m.monto', '<=', $monto_hasta);
                })
                ->when($request->input('id_banco'), function ($query, $id_banco) {
                    return $query->where('bc.id_banco', $id_banco);
                });

            $movimientos = $query
                ->orderByDesc('m.fecha')
                ->orderByDesc('m.id')
                ->get();

            return response()->json($movimientos, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener los movimientos'], 500);
        }
    }

    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * personas también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function movimientoTipo()
    {
        return $this->belongsTo(MovimientosTipos::class, 'id_movimiento_tipo');
    }

    public function bancoCuenta()
    {
        return $this->belongsTo(BancosCuentas::class, 'id_banco_cuenta');
    }

    public function persona()
    {
        return $this->belongsTo(Personas::class, 'id_persona');
    }
}
