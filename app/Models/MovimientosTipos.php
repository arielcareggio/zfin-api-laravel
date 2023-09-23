<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosTipos extends Model
{
    use HasFactory;

    protected $fillable = ['id_cuenta', 'id_tipo', 'name'];

    protected $primaryKey = 'id';

    protected $table = 'movimientos_tipos';

    static public function getMovimientosTipos($request)
    {
        try {
            $cuentas = MovimientosTipos::from('movimientos_tipos as m')
                ->select(
                    'm.*',
                    'c.name as name_cuenta'
                )
                ->join('cuentas as c', 'c.id', '=', 'm.id_cuenta')
                ->where('c.id_user', request()->user()->id)
                ->orWhere('m.id_cuenta', 1) // id_cuenta = 1: Cuenta por defecto
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where('m.name', 'like', '%' . $name . '%');
                })
                ->when($request->input('id_cuenta'), function ($query, $id_cuenta) {
                    return $query->where('m.id_cuenta', $id_cuenta);
                })
                ->when($request->input('id_tipo'), function ($query, $id_tipo) {
                    return $query->where('m.id_tipo', $id_tipo);
                })
                ->orderBy('m.id_tipo')
                ->orderBy('m.name')
                ->get();

            return response()->json($cuentas, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener los Tipos de Movimientos'], 500);
        }
    }
    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * personas también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function cuenta()
    {
        return $this->belongsTo(Cuentas::class, 'id_cuenta');
    }
}
