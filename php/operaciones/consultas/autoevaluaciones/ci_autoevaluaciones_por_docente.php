<?php
class ci_autoevaluaciones_por_docente extends planta_ci
{
	protected $s__filtro;
	
	function conf()
	{
		$this->pantalla('pant_inicial')->eliminar_dep('form_resp');
		$this->pantalla('pant_inicial')->eliminar_dep('form_act');   
	}
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
		$datos[0] = toba::consulta_php('co_autoevaluaciones')->get_ficha_de_docente($persona,$ciclo);
		$cuadro->set_titulo('Ficha docente: '.$datos[0]['nombre_completo']);
		if (!isset($datos[0]['nombre_completo']))
			return;
		$cuadro->set_datos($datos);
	}
	
	function evt__cuadro__seleccion($seleccion)
	{
		toba::memoria()->set_dato('path',$seleccion['ficha_docente_path']);
	}
	
	//-----------------------------------------------------------------------------------
	//---- cuadro_act -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_act(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		if ($where == '1=1')
			return;
		$datos_filtro = $this->dep('filtro')->get_datos();
		$persona = $datos_filtro['persona']['valor'];
		$ciclo = $datos_filtro['ciclo_lectivo']['valor'];
		$datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluaciones_por_act_persona($persona,$ciclo);
		$cuadro->set_titulo('Autoevaluaciones: '.$datos['nombre_completo']);
		$cuadro->set_datos($datos);        
	}

	function evt__cuadro_act__seleccion($seleccion)
	{
		toba::memoria()->set_dato('asignacion',$seleccion['asignacion']); 
		if ($seleccion['responsable'] == 'S') {
			// es responsable
			$this->pantalla('pant_inicial')->agregar_dep('form_resp');
		} else {
			// NO es responsable
			$this->pantalla('pant_inicial')->agregar_dep('form_act');
		}
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
		$path = toba::memoria()->get_dato('path'); 
		if (!isset($path))
			return;  
		
		// el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
		$nombre = substr($path,19);
		$dir_tmp = toba::proyecto()->get_www_temp();
		exec("cp '". $path. "' '" .$dir_tmp['path']."/".$nombre."'");
		$temp_archivo = toba::proyecto()->get_www_temp($nombre);
		$tamanio = round(filesize($temp_archivo['path']) / 1024);
		$datos['ficha_docente_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";

		$form->set_datos($datos);         
	}    
	
	//-----------------------------------------------------------------------------------
	//---- form_act ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_act(planta_ei_formulario $form)
	{
		$asignacion = toba::memoria()->get_dato('asignacion'); 
		if (!isset($asignacion))
			return;
		$datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);
		$form->set_datos($datos); 
	}

	//-----------------------------------------------------------------------------------
	//---- form_resp ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_resp(planta_ei_formulario $form)
	{
		$asignacion = toba::memoria()->get_dato('asignacion'); 
		if (!isset($asignacion))
			return;
		$datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);

		if ($datos['informe_catedra'] == 'S') {
			// el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
			$nombre = substr($datos['informe_catedra_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos['informe_catedra_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024);
			$datos['informe_catedra_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";         
		} 
		if ($datos['programa'] == 'S') {
			// el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
			$nombre = substr($datos['programa_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos['programa_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024);
			$datos['programa_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";    
		}
		if ($datos['informe_otros'] == 'S') {
			// el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
			$nombre = substr($datos['informe_otros_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos['informe_otros_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024);
			$datos['informe_otros_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";    
		}
		
		$form->set_datos($datos); 
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