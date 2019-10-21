<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class act_designaciones
{
    function cambiar_estado($designacion, $estado) {
        $sql = "UPDATE designaciones
                SET estado = $estado
                WHERE designacion = $designacion
                ";
        toba::db()->consultar($sql);
    }
    
    function cambiar_estado_persona($persona, $estado) {
        $sql = "UPDATE designaciones
                SET estado = $estado
                WHERE persona = $persona
                AND estado in (".comunes::estado_activo.",".comunes::estado_con_licencia.")";
        toba::db()->consultar($sql);
    }    

    function insertar_modificacion($designacion_nueva, $designacion_modificada) {
        $sql = "INSERT INTO designaciones_modificadas (desig_nueva, desig_modificada) values
                ($designacion_nueva, $designacion_modificada)
                ";
        toba::db()->consultar($sql);
    }
}