<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    use HasFactory;

    protected $fillable = ['id_user', 'name', 'eliminado'];

    protected $primaryKey = 'id';

    protected $table = 'cuentas';

    protected $casts = [
        'eliminado' => 'boolean',
    ];
}
