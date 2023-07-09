<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierresMensuales extends Model
{
    use HasFactory;

    protected $fillable = ['id_cuenta', 'id_banco_cuenta', 'total', 'fecha'];

    protected $primaryKey = 'id';

    protected $table = 'cierres_mensuales';

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

    public function bancosCuentas()
    {
        return $this->belongsTo(BancosCuentas::class, 'id_banco_cuenta');
    }
}
