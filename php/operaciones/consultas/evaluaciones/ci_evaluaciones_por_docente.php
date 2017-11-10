<?php
class ci_evaluaciones_por_docente extends planta_ci
{
	protected $s__filtro;
	
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		if ($where == '1=1')
			return;
		$datos_filtro = $this->dep('filtro')->get_datos();
		$persona = $datos_filtro['persona']['valor'];
		$ciclo = $datos_filtro['ciclo_lectivo']['valor'];
		$datos = toba::consulta_php('co_evaluaciones')->get_evaluaciones_de_persona_por_ciclo($persona,$ciclo);
		//$cuadro->set_titulo('Evaluaciones del docente');
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
?>