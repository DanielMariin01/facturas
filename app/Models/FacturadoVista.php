<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturadoVista extends Model
{
    protected $table = 'v_facturado'; // 👈 Tu vista SQL

    public $incrementing = false; // si no tiene auto incremento
    public $timestamps = false;
}
