<?php
class ci_administrar_perfiles extends planta_ci
{
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
	
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		$datos = toba::consulta_php('co_personas')->get_personas($where);
		$datos_aux = array();
		
		foreach ($datos as $fila) {
			if ($fila['estado_docente'] == 1 ) {
				$fila['estado_docente_desc'] = '<font color=green><b>'.$fila['estado_docente_desc'].'</b></font>';
			} else {
				$fila['estado_docente_desc'] = '<font color=red><b>'.$fila['estado_docente_desc'].'</b></font>';
			}
			if ($fila['estado_nodocente'] == 1 ) {
				$fila['estado_nodocente_desc'] = '<font color=green><b>'.$fila['estado_nodocente_desc'].'</b></font>';
			} else {
				$fila['estado_nodocente_desc'] = '<font color=red><b>'.$fila['estado_nodocente_desc'].'</b></font>';
			}
			if ($fila['estado_externo'] == 1 ) {
				$fila['estado_externo_desc'] = '<font color=green><b>'.$fila['estado_externo_desc'].'</b></font>';
			} else {
				$fila['estado_externo_desc'] = '<font color=red><b>'.$fila['estado_externo_desc'].'</b></font>';
			}
			$datos_aux[] = $fila; 
		}
		$cuadro->set_datos($datos_aux);
	}
	
	function evt__cuadro__seleccion($seleccion)
	{
		$this->relacion()->cargar($seleccion);
		$this->set_pantalla('edicion');
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
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__procesar()
	{
		$this->dep('relacion')->sincronizar();
		$this->dep('relacion')->resetear();
		$this->set_pantalla('seleccion');
	}

	function evt__cancelar()
	{
		$this->dep('relacion')->resetear();
		$this->set_pantalla('seleccion');
	}    
	
	//-----------------------------------------------------------------------------------
	//---- form_ml ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml(planta_ei_formulario_ml $form_ml)
	{
		$datos = $this->tabla('personas_perfiles')->get_filas();
		$datos_ordenados = rs_ordenar_por_columna($datos, 'fecha_carga');
		$form_ml->set_datos($datos_ordenados);
	}

	function evt__form_ml__modificacion($datos)
	{
		$this->tabla('personas_perfiles')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
		$datos = $this->tabla('personas')->get();
		$form->set_datos($datos);
	}

}
?>