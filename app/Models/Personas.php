<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personas extends Model
{
    use HasFactory;

    protected $fillable = ['id_cuenta', 'name'];

    protected $primaryKey = 'id';

    protected $table = 'personas';

    static public function getPersonas($request)
    {
        try {
            $persona = Personas::from('personas as p')
                ->select('p.*')
                ->join('cuentas as c', 'c.id', '=', 'p.id_cuenta')
                ->where('c.id_user', request()->user()->id)
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where('p.name', 'like', '%' . $name . '%');
                })
                ->when($request->input('id_cuenta'), function ($query, $id_cuenta) {
                    return $query->where('p.id_cuenta', $id_cuenta);
                })
                ->when($request->input('id_persona'), function ($query, $id_cuenta) {
                    return $query->where('p.id', $id_cuenta);
                })
                ->orderBy('p.name')
                ->get();

            return response()->json($persona, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener las Personas'], 500);
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
