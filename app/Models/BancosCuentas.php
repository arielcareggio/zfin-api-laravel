<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancosCuentas extends Model
{
    use HasFactory;

    protected $fillable = ['id_banco', 'name', 'nro_cuenta'];

    protected $primaryKey = 'id';

    protected $table = 'bancos_cuentas';

    static public function getBancosCuentas($request){
        try {
            $bancoCuenta = BancosCuentas::from('bancos_cuentas as bc')
                ->select('bc.*')
                ->join('bancos as b', 'b.id', '=', 'bc.id_banco')
                ->join('cuentas as c', 'c.id', '=', 'b.id_cuenta')
                ->where('c.id_user', request()->user()->id)
                //when: agrega una condición a la consulta solo si se cumple, si se cumple entonces ejecuta la función
                ->when($request->input('id_banco_cuenta'), function ($query, $id_banco_cuenta) {
                    return $query->where('bc.id', $id_banco_cuenta);
                })
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where('bc.name', 'like', '%' . $name . '%');
                })
                ->when($request->input('id_banco'), function ($query, $id_banco) {
                    return $query->where('bc.id_banco', $id_banco);
                })
                ->when($request->input('nro_cuenta'), function ($query, $nro_cuenta) {
                    return $query->where('bc.nro_cuenta', 'like', '%' . $nro_cuenta . '%');
                })

                ->orderBy('bc.name')
                ->get();

            return response()->json($bancoCuenta, 200);
        } catch (\Exception $e) {
            // Ocurrió un error al crear el registro
            return response()->json(['error' => 'Error al crear el registro'], 500);
        }
    }
    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * Bancos también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function banco()
    {
        return $this->belongsTo(Bancos::class, 'id_banco');
    }
}
