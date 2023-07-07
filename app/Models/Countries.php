<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Countries
 *
 * @property int $id
 * @property string $name
 * @property string $iso2
 * @property string $iso3
 * @property string|null $phone_code
 * @method static \Illuminate\Database\Eloquent\Builder|Countries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Countries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Countries query()
 * @method static \Illuminate\Database\Eloquent\Builder|Countries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Countries whereIso2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Countries whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Countries whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Countries wherePhoneCode($value)
 * @mixin \Eloquent
 */
class Countries extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'iso2', 'iso3', 'phone_code'];

    protected $primaryKey = 'id';

    protected $table = 'countries';

    public $timestamps = false;
}
