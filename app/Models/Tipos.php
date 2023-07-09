<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos extends Model
{
    use HasFactory;

    protected $fillable = ['tipos'];

    protected $primaryKey = 'id';

    protected $table = 'tipos';
}
