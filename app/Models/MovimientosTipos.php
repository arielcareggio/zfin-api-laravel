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
