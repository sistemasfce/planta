<?php
class ci_cantidad_docentes_designaciones extends planta_ci
{
    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('co_acreeditacion')->get_cantidad_docentes_designaciones();
        $cuadro->set_datos($datos);
    } 
}
?>
