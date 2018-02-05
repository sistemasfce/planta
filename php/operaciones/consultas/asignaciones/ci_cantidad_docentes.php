<?php
class ci_cantidad_docentes extends planta_ci
{
	protected $s__filtro;
	
	//-----------------------------------------------------------------------------------
	//---- cuadro_des ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
            $where = $this->dep('filtro')->get_sql_where();
            if ($where != '1=1') {
                $datos = toba::consulta_php('co_asignaciones')->get_cantidad($where);
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
        

