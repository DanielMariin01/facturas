<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Radicado extends Model
{
     protected $table = 'radicado'; // Nombre de la tabla
    protected $primaryKey = 'id_radicado';
   

        protected $fillable = [
    
        'archivo',
        'codigo', 
        //'fk_facturado'

    ];


     public function facturado()
    {
        return $this->hasMany(Facturado::class, 'fk_radicado', 'id_radicado');
    }
}
