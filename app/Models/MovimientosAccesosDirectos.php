<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosAccesosDirectos extends Model
{
    use HasFactory;

    protected $fillable = ['id_movimiento_tipo', 'id_banco_cuenta', 'id_persona', 'name', 'monto', 'url_archivo'];

    protected $primaryKey = 'id';

    protected $table = 'movimientos_accesos_directos';

    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * personas también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function movimientoTipo()
    {
        return $this->belongsTo(MovimientoTipo::class, 'id_movimiento_tipo');
    }

    public function bancoCuenta()
    {
        return $this->belongsTo(BancoCuenta::class, 'id_banco_cuenta');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
}
