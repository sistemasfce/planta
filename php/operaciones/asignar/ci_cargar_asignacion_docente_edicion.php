<?php
class ci_cargar_asignacion_docente_edicion extends planta_ci
{
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
                    //if ($fila['estado'] == 3)
                    //        continue;
                    $fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

                    if ($fila['designacion_tipo'] == 1 and $fila['designacion'] != null and ($fila['estado'] == 1 or $fila['estado'] == 5)) {
                            $horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
                            $fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
                    }
                    $suma_asignaciones = toba::consulta_php('co_asignaciones')->get_horas_asignadas_x_designacion($fila['designacion']);
                    $fila['a_definir'] = $fila['carga_horaria_real'] - $suma_asignaciones['suma'];

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
            $datos_ordenados = rs_ordenar_por_columna($this->datos_para_cuadro, 'resolucion_fecha');
            $this->datos_para_cuadro = $datos_ordenados;
            $cuadro->set_datos($datos_ordenados);
	}  
	
	function evt__cuadro_des__seleccion($seleccion)
	{
		$this->tabla('designaciones')->set_cursor($seleccion);
		$this->hay_cambios = true;  
	} 
	
	function conf_evt__cuadro_des__seleccion(toba_evento_usuario $evento, $fila)
	{
		/*
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
		*/
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
			if ($dat['estado'] == 3)
				continue;
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
                $datos_ordenados = rs_ordenar_por_columna($datos_para_cuadro, 'resolucion_fecha');
		$cuadro->set_datos($datos_ordenados);
	}
	
	//-----------------------------------------------------------------------------------
	//---- form_asig ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_asig(planta_ei_formulario $form)
	{
		$datos = array();
		if ($this->tabla('designaciones')->hay_cursor()) {
			$datos_des =$this->tabla('designaciones')->get();
			$datos['carrera_academica'] = $datos_des['carrera_academica'];
			$form->set_datos($datos);
		}      
	}    
	
	function evt__form_asig__alta($datos)
	{
		if ($this->tabla('designaciones')->hay_cursor()) {
			$datos_desig = $this->tabla('designaciones')->get();
			$datos['persona'] = $datos_desig['persona'];

			if ($datos_desig['designacion_tipo'] == 1 and $datos_desig['designacion'] != null and ($datos_desig['estado'] == 1 or $datos_desig['estado'] == 5)) {
				$horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($datos_desig['designacion']);
				$datos_desig['carga_horaria_real'] = $datos_desig['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
			}
			$suma_asignaciones = toba::consulta_php('co_asignaciones')->get_horas_asignadas_x_designacion($datos_desig['designacion']);
			$a_definir = $datos_desig['carga_horaria_real'] - $suma_asignaciones['suma'];
			
			if ($datos['carga_horaria'] > $a_definir) {
				$this->informar_msg("Supera el limite de horas a utilizar","info");
			//    return;
			}
			$datos['ciclo_lectivo'] = substr($datos['fecha_desde'], 0, 4);
			$this->tabla('asignaciones')->nueva_fila($datos);
			$this->hay_cambios = true;
		} else {;
			$this->informar_msg("Debe seleccionar una designación","error");
		}
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