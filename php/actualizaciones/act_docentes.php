<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class act_docentes
{
    function inactivar_docente($persona) {
        $perfil = comunes::perfil_docente;
        $perfil_estado = comunes::perfil_estado_jubilado;
        $sql = "INSERT INTO personas_perfiles (persona, perfil, perfil_estado)
            VALUES ($persona, $perfil, $perfil_estado)
                ";
        toba::db()->consultar($sql);
    }
}