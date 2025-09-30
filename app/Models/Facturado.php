<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturado extends Model
{
  // Declarar llave primaria
    protected $table = 'facturado'; // Nombre de la tabla
    protected $primaryKey = 'id_facturado';
   

       protected $fillable = [
        'Dcto',
        'Tipo',
        'T_Dcto',
        'Paciente',
        'Ingreso',
        'Fec_Ingreso',
        'Fec_Egreso',
        'Estado',
        'Factura',
        'Tipo_Documento',
        'Fecha_Factura',
        'Fecha',
        'Grup_Cir',
        'Cod_Proced',
        'Tipo_Proc',
        'Codi_Proc',
        'Nombre',
        'PNP',
        'UVR',
        'Porcentaje',
        'Cant',
        'Vl_Unit',
        'Vl_Total',
        'Tipo_Cargo',
        'Cod_CC',
        'Ce_Cos',
        'Id_Honor',
        'Honor',
        'Cod_Med',
        'Medico',
        'Cod_Esp',
        'Especialidad',
        'Convenio_Id',
        'Convenio',
        'NIT',
        'EPS',
        'CodDx',
        'Diagnostico',
        'Anato',
        'PART',
        'SERVICIO',
        'Codigo_CUM',
        'Registro_INVIMA',
        'Numero_Cita',
        'Mes_Ingreso',
        'Año_ingreso',
        'agrupador',
        'Mes_Egreso',
    ];

     public function radicado()
    {
        return $this->belongsTo(Radicado::class, 'fk_radicado', 'id_radicado');
    }


}
