<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacienteAcostado extends Model
{
    protected $table = 'facturado'; // Nombre de la tabla
    protected $primaryKey = 'id_facturado';
   

        protected $fillable = [
        'dcto',
        'T_Dcto',
        'ingreso',
        'fecha_ingreso',
        'Tipo_documento',
        'tip_procedimiento',
        'codigo_procedimiento',
        'nombre',
        'estado',
        'cantidad',
        'valor_unitario',
        'valor_total',
        'convenio',
        'nit',
        'eps',
        'diagnostico',
        'servicio',
        'fk_radicado'
    ];
}
