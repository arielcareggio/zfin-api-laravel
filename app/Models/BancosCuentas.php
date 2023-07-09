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
