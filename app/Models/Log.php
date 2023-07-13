<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['ip', 'id_user', 'metodo', 'is_error', 'html_code', 'mensaje', 'json_entrada', 'json_salida', 'headers', 'origen'];

    protected $primaryKey = 'id';

    protected $table = 'log';

    /**
     * Para la eliminación en cascada.
     * Cuando se elimine un registro en la tabla cuentas, los registros relacionados en la tabla
     * personas también se eliminarán automáticamente debido a la configuración onDelete('cascade')
     * indicada en las migraciones
     */
    public function users()
    {
        return $this->belongsTo(MovimientosTipos::class, 'id_user');
    }
}
