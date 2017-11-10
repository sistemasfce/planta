<?php
class ci_modificar_asignacion_externo_edicion extends planta_ci
{
	protected $hay_cambios;
	
	//-------------------------------------------------------------------------
	function relacion()
	{
		return $this->controlador->dep('relacion');
	}
	
	//-------------------------------------------------------------------------
	function tabla($id)
	{
		return $this->controlador->dep('relacion')->tabla($id);    
	}
	
	function ini()
	{
		$this->hay_cambios = false;    
	}   
	
	//-----------------------------------------------------------------------------------
	//---- cuadro_asig ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_asig(planta_ei_cuadro $cuadro)
	{
		$datos_para_cuadro = array();
		$aux = array();
		$datos = $this->tabla('asignaciones')->get_filas();
		foreach ($datos as $dat) {
			$fila = $dat;
			$fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

			if ($dat['estado'] == 1  or $dat['estado'] == 6) {
				$fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
			}
			if ($dat['estado'] == 3 ) {
				$fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
			}  else {
				$fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
			}
			$datos_para_cuadro[] = $fila;
		}
		$cuadro->set_datos($datos_para_cuadro);
	}
	
	function evt__cuadro_asig__seleccion($seleccion)
	{
		$this->tabla('asignaciones')->set_cursor($seleccion);
		$this->hay_cambios = true; 
	}     
	
	//-----------------------------------------------------------------------------------
	//---- form_asig ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_asig(planta_ei_formulario $form)
	{
		if ($this->tabla('asignaciones')->hay_cursor()) {
			$datos =$this->tabla('asignaciones')->get();
			$form->set_datos($datos);
		}      
	}    
	
	function evt__form_asig__alta($datos)
	{
		$this->tabla('asignaciones')->nueva_fila($datos);
		$this->hay_cambios = true;
	}

	function evt__form_asig__baja()
	{
		$this->tabla('asignaciones')->set(null);
	}

	function evt__form_asig__modificacion($datos)
	{
		$this->tabla('asignaciones')->set($datos);
		$this->evt__form_asig__cancelar();
	}

	function evt__form_asig__cancelar()
	{
		$this->tabla('asignaciones')->resetear_cursor();
	}    
	
	//-----------------------------------------------------------------------------------
	//-------------- --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function get_hay_cambios()
	{
		return $this->hay_cambios;
	}     
}
?>