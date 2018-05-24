<?php
class ci_categorias_y_dedicaciones extends planta_ci
{
    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('co_acreeditacion')->get_categorias_dedicaciones();
        $cuadro->set_datos($datos);
    } 
}
?>
