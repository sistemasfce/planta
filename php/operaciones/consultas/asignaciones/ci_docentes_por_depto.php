<?php
class ci_docentes_por_depto extends planta_ci
{
    protected $s__filtro;

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $filtro = $this->dep('filtro')->get_sql_where();
        if ($filtro != '1=1') {
            $datos = toba::consulta_php('co_asignaciones')->get_docentes_por_depto($filtro);
            $cuadro->set_datos($datos);
        }
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
?>