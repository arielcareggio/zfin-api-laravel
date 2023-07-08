<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    use HasFactory;

    protected $fillable = ['id_cuenta', 'id_countrie', 'name', 'eliminado'];

    protected $primaryKey = 'id';

    protected $table = 'bancos';

    protected $casts = [
        'eliminado' => 'boolean',
    ];

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
