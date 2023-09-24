<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosAccesosDirectos extends Model
{
    use HasFactory;

    protected $fillable = ['id_tipo', 'id_movimiento_tipo', 'id_banco_cuenta', 'id_persona', 'name', 'monto', 'url_archivo'];

    protected $primaryKey = 'id';

    protected $table = 'movimientos_accesos_directos';

    static public function getMovimientosAccesosDirectos($request){
        try {

            $accesosDirectos = MovimientosAccesosDirectos::from('movimientos_accesos_directos as ma')
                ->select('ma.*')
                ->join('personas as p', 'p.id', '=', 'ma.id_persona')
                ->join('cuentas as c', 'c.id', '=', 'p.id_cuenta')
                ->where('c.id_user', request()->user()->id)
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('id_tipo'), function ($query, $id_tipo) {
                    return $query->where('ma.id_tipo', $id_tipo);
                })
                ->when($request->input('id_movimiento_tipo'), function ($query, $id_movimiento_tipo) {
                    return $query->where('ma.id_movimiento_tipo', $id_movimiento_tipo);
                })
                ->when($request->input('id_banco_cuenta'), function ($query, $id_banco_cuenta) {
                    return $query->where('ma.id_banco_cuenta', $id_banco_cuenta);
                })
                ->when($request->input('id_persona'), function ($query, $id_persona) {
                    return $query->where('ma.id_persona', $id_persona);
                })
                ->orderBy('ma.id_tipo')
                ->orderBy('ma.name')
                ->get();

            return response()->json($accesosDirectos, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener los Accesos Directos de Movimientos'], 500);
        }
    }
    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * personas también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function tipo()
    {
        return $this->belongsTo(Tipos::class, 'id_tipo');
    }
    
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
