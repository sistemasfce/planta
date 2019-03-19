<?php
class ci_ver_personas_actividades extends planta_ci
{
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
            $datos = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo);
            $cuadro->set_datos($datos);        
    }    
}