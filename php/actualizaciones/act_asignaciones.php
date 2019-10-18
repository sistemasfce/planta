<?php

class act_asignaciones
{
    function cambiar_estado_por_desig($designacion, $estado) {
        $sql = "UPDATE asignaciones
                SET estado = $estado
                WHERE designacion = $designacion
                ";
        toba::db()->consultar($sql);
    }

}