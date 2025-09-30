<?php

namespace App\Enums;

enum Estado: string
{
    case FACTURADO = 'Facturado';
    case INGRESO_ABIERTO = 'Ingreso_abierto';
    case PACIENTE_ACOSTADO= 'Paciente_acostado';
    case RADICADO= 'Radicado';
   
 

    /**
     * Obtiene el nombre legible para el usuario de cada estado.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::FACTURADO=> 'Facturado',
            self::INGRESO_ABIERTO => 'Ingreso_abierto',
            self::PACIENTE_ACOSTADO => 'Paciente_acostado',
            self::RADICADO => 'Radicado',
      
    
        };
    }

    /**
     * Obtiene el color asociado a cada estado (útil para Filament).
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
         self::FACTURADO => 'success',       // Verde: proceso completado correctamente
         self::INGRESO_ABIERTO => 'warning', // Amarillo: algo en curso, aún no cerrado
         self::PACIENTE_ACOSTADO => 'info',  // Azul: estado informativo/neutral
         self::RADICADO => 'info',        // Azul oscuro (o similar): documento oficializado

         
        };
    }
}
