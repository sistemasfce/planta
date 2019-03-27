<?php
class ci_confirmados_por_fecha extends planta_ci
{
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();  
        if ($where == '1=1')
            return;
        $filtro = $this->dep('filtro')->get_datos();
        $fecha = $filtro['fecha']['valor'];
        $datos = toba::consulta_php('co_autoevaluaciones')->get_fichas_por_fecha($where);
        $cuadro->set_datos($datos);       
    }    
    
    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro(planta_ei_filtro $filtro)
    {
        if (isset($this->s__filtro)) {
            $filtro->set_datos($this->s__filtro);
        }
    }

    function evt__filtro__filtrar($datos)
    {
        $this->s__filtro = $datos;
    }

    function evt__filtro__cancelar()
    {
        unset($this->s__filtro);
    }      
}



