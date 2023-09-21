<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    use HasFactory;

    protected $fillable = ['id_cuenta', 'id_countrie', 'name'];

    protected $primaryKey = 'id';

    protected $table = 'bancos';

    /* protected $casts = [
        'eliminado' => 'boolean',
    ]; */

    static public function getBancos($request)
    {
        try {
            $bancos = Bancos::from('bancos as b')
                ->select(
                    'b.*',
                    'c.name as name_cuenta',
                    'co.name as name_countrie'
                )
                ->join('cuentas as c', 'c.id', '=', 'b.id_cuenta')
                ->join('countries as co', 'co.id', '=', 'b.id_countrie')
                ->where('c.id_user', request()->user()->id)
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where('b.name', 'like', '%' . $name . '%');
                })
                ->when($request->input('id_banco'), function ($query, $id_banco) {
                    return $query->where('b.id', $id_banco);
                })
                ->when($request->input('id_cuenta'), function ($query, $id_cuenta) {
                    return $query->where('b.id_cuenta', $id_cuenta);
                })
                ->when($request->input('id_countrie'), function ($query, $id_countrie) {
                    return $query->where('b.id_countrie', $id_countrie);
                })

                ->orderBy('b.name')
                ->get();

            return response()->json($bancos, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener los Bancos'], 500);
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

    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla countrie, los registros relacionados en la tabla
     * Bancos también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function countrie()
    {
        return $this->belongsTo(Countries::class, 'id_countrie');
    }
}
