<?php
class ci_ver_personas_actividades extends planta_ci
{
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $param = toba::memoria()->get_parametros();
        $dimension = $param['dimension'];
        $columna = $param['columna'];
        $ciclo = 2018;
        if ($columna == 1) {
            $datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0);
            $cuadro->set_datos($datos);        
        }
    }    
}