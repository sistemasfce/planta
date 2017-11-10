<?php
class ci_horas_neto extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		$datos = toba::consulta_php('co_designaciones')->get_horas_neto($where);
		$completo = array();
		foreach ($datos as $dat) {
			$aux = $dat;
			$aux['horas_neto'] = $dat['horas_desig'] - $dat['horas_licenciadas'];
			$aux['horas_utilizadas'] = $dat['horas_asignadas'];
			$aux['horas_a_definir'] = $aux['horas_neto'] - $aux['horas_utilizadas'];   
			$completo[] = $aux;
		} 
		$cuadro->set_datos($completo);
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