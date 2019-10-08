<?php

class act_designaciones
{
    function cambiar_estado($designacion, $estado) {
        $sql = "UPDATE designaciones
                SET estado = $estado
                WHERE designacion = $designacion
                ";
        toba::db()->consultar($sql);
    }

    function insertar_modificacion($designacion_nueva, $designacion_modificada) {
        $sql = "INSERT INTO designaciones_modificadas (desig_nueva, desig_modificada) values
                ($designacion_nueva, $designacion_modificada)
                ";
        toba::db()->consultar($sql);
    }    
}