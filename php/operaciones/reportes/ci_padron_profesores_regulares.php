<?php
class ci_padron_profesores_regulares extends planta_ci
{
	protected $s__filtro;
	
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		if ($where != '1=1') {
			$datos_filtro = $this->dep('filtro')->get_datos();
			$ubicacion = $datos_filtro['ubicacion']['valor'];
			$ubicacion_desc = toba::consulta_php('co_parametros')->get_ubicacion_nombre($ubicacion);
			toba::memoria()->set_dato('ubicacion_desc',$ubicacion_desc['descripcion']);
			$datos = toba::consulta_php('co_designaciones')->get_padron_profesores_regulares($where);
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
	
	//-----------------------------------------------------------------------------------
	//---- JASPER -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	function vista_jasperreports($report)
	{    
		$report->set_nombre_archivo('Padron de profesores regulares');
		$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
		$path = $path_toba.'padron_profesores.jasper';
		$report->set_path_reporte($path);

		$report->set_parametro('titulo', 'S', 'Padrón de profesores regulares');        
		$ubicacion_desc = toba::memoria()->get_dato('ubicacion_desc');
		$report->set_parametro('ubicacion_desc', 'S', $ubicacion_desc);
		$xml = $this->dep('cuadro')->vista_xml();
		$report->set_xml(utf8_e_seguro($xml));
		$report->completar_con_datos();  
	}
	
}
?>