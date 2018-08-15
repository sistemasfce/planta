<?php
class ci_desempenio extends planta_ci
{
    function conf__pant_inicial(toba_ei_pantalla $pantalla)
    {
        $bloqueado = toba::memoria()->get_dato('bloqueado');
        if ($bloqueado == 'S')
            $this->dep('form_desemp')->evento('evaluador')->desactivar();
    }
    //-----------------------------------------------------------------------------------
    //---- form_desemp ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__form_desemp__evaluador($datos)
    {
        $this->controlador()->set_pantalla('pant_evaluar');    
    }

    function evt__form_desemp__mis_evaluaciones($datos)
    {
        $this->controlador()->set_pantalla('pant_miseval');
    }    
}
?>