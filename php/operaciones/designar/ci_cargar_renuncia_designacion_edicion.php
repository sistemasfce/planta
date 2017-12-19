<?php
class ci_cargar_renuncia_designacion_edicion extends planta_ci
{
	protected $s__desig_seleccionada;
	protected $hay_cambios;
	protected $datos_para_cuadro = array();
	
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
	
	function get_hay_cambios()
	{
		return $this->hay_cambios;
	}   
	//-----------------------------------------------------------------------------------
	//---- cuadro_des ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_des(planta_ei_cuadro $cuadro)
	{
		$datos_para_cuadro = array();
		$aux = array();
		$datos = $this->tabla('designaciones')->get_filas();
		
		foreach ($datos as $dat) {
			$fila = $dat;
			$fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

			if ($fila['designacion_tipo'] == 1 and ($fila['estado'] == 1 or $fila['estado'] == 5)) {
				$horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
				$fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
			}
			
			if ($dat['estado'] == 1  or $dat['estado'] == 6) {
				$fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
			}
			if ($dat['estado'] == 3 ) {
				$fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
			}  else {
				$fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
			}
			$this->datos_para_cuadro[] = $fila;
		}
		$datos_ordenados = rs_ordenar_por_columna($this->datos_para_cuadro, 'fecha_desde');
                $this->datos_para_cuadro = $datos_ordenados;
		$cuadro->set_datos($this->datos_para_cuadro);
	}
	
	function conf_evt__cuadro_des__seleccion(toba_evento_usuario $evento, $fila)
	{
		
		$datos = $this->datos_para_cuadro;
		if ($datos[$fila]['designacion_tipo'] != 1) {
			$evento->anular(); 
		} else {
			if ($datos[$fila]['estado'] == 3 or $datos[$fila]['estado'] == 4) {
				$evento->anular(); 
			}
			else {
				$evento->mostrar();  
			}  
		}   
		
	}  
	
	function evt__cuadro_des__seleccion($seleccion)
	{
		$datos_origen = toba::consulta_php('co_designaciones')->get_designacion_tabla($seleccion['designacion']);
		if ($datos_origen['designacion_tipo'] != 1 and ($datos_origen['estado'] != 1 and $datos_origen['estado'] != 5)) {
			$this->informar_msg("Debe seleccionar una designación activa","error");
			return;
		}
		$this->s__desig_seleccionada = $seleccion['designacion'];
		$this->hay_cambios = true;  

	}
	
	//-----------------------------------------------------------------------------------
	//---- form_des ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------


	function evt__form_des__alta($datos)
	{
		if ($this->s__desig_seleccionada == null) {
			$this->informar_msg("Debe seleccionar una designación","error");
		} else {
			$datos_origen = toba::consulta_php('co_designaciones')->get_designacion_tabla($this->s__desig_seleccionada);
			$datos['persona'] = $datos_origen['persona'];
			$datos['espacio_disciplinar'] = $datos_origen['espacio_disciplinar'];
			$datos['departamento'] = $datos_origen['departamento'];
			$datos['categoria'] = $datos_origen['categoria'];
			$datos['caracter'] = $datos_origen['caracter'];
			$datos['ubicacion'] = $datos_origen['ubicacion'];
			$datos['carrera_academica'] = $datos_origen['carrera_academica'];
			$datos['designacion_padre'] = $datos_origen['designacion'];
			$this->tabla('designaciones')->nueva_fila($datos);
			$this->s__desig_seleccionada = null;
		}
	}
	
	
	function conf__form_des(planta_ei_formulario $form)
	{        
		$datos = array();
		if ($this->s__desig_seleccionada == null) {
			
		} else {
			$where = ' designacion = '.$this->s__desig_seleccionada;
			$dat = toba::consulta_php('co_designaciones')->get_designaciones($where);
			$datos['titulo'] = $dat[0]['espacio_disciplinar_desc']. ' - '.$dat[0]['categoria_desc']. ' - '.$dat[0]['dedicacion_desc']. ' - ' .$dat[0]['resolucion_desc'];
			$form->set_datos($datos);
		}
	}

}
?>