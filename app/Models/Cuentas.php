<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    use HasFactory;

    protected $fillable = ['id_user', 'name'];

    protected $primaryKey = 'id';

    protected $table = 'cuentas';

    static public function getCuentas($request){
        try {
            $cuentas = Cuentas::from('cuentas as c')
                ->select('c.*')
                ->where('c.id_user', request()->user()->id)
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where('c.name', 'like', '%' . $name . '%');
                })
                ->orderBy('c.name')
                ->get();

            return response()->json($cuentas, 200);

        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al obtener las Cuentas'], 500);
        }
    }
}
