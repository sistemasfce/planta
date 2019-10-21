<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class act_asignaciones
{
    function cambiar_estado_por_desig($designacion, $estado) {
        $sql = "UPDATE asignaciones
                SET estado = $estado
                WHERE designacion = $designacion
                ";
        toba::db()->consultar($sql);
    }
    
    function cambiar_estado_persona($persona, $estado) {
        $sql = "UPDATE asignaciones
                SET estado = $estado
                WHERE persona = $persona
                    AND estado in (".comunes::estado_activo.",".comunes::estado_con_licencia.")";
        toba::db()->consultar($sql);
    }
}