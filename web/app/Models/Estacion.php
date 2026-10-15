<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    protected $table = 'estaciones';

    protected $fillable = ['nombre', 'latitud', 'longitud'];

    protected function casts(): array
    {
        return ['latitud' => 'decimal:7', 'longitud' => 'decimal:7'];
    }
}
