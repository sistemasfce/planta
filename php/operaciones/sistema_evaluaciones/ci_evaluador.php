<?php
class ci_evaluador extends planta_ci
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
		$persona = toba::memoria()->get_dato('persona');
		$nombre = toba::memoria()->get_dato('nombre');
		$cuadro->set_titulo('Evaluador: '.$nombre);
		$ciclo = toba::memoria()->get_dato('ciclo');
		// busco los docentes que tengo a cargo, no hace falta pasar el ciclo lectivo
		// porque solo busco asignaciones activas
		$docentes_a_cargo = toba::consulta_php('co_evaluaciones')->get_docentes_a_cargo($persona,$ciclo);   
		// buscar para todas mis actividades donde soy responsable
		// si la actividad esta en la tabla actividades_a_evaluar
		// buscar los docentes responsables de esas actividades
		$actividades_a_evaluar = toba::consulta_php('co_evaluaciones')->get_actividades_a_evaluar($persona,$ciclo);
		$doc = array_merge($docentes_a_cargo,$actividades_a_evaluar);
		// buscar para todas mis actividades donde soy responsable
		// si la actividad esta en la tabla ambitos_a_evaluar
		// buscar los docentes responsables de esas actividades
		$ambitos_a_evaluar = toba::consulta_php('co_evaluaciones')->get_ambitos_a_evaluar($persona,$ciclo);
		$doc = array_merge($doc,$ambitos_a_evaluar);
		
		// buscar en la tabla de excepciones por actividad
		
		// si tengo resultados de la consulta anterior
		// buscar los docentes de esas actividades
		
		// buscar en la tabla de excepciones por persona
		
		// si soy responsable de algun cargo, buscar si debo evaluar 
		// a algun docente que no tenga responsable en su actividad
		
		
		if (!isset($doc[0])) {
			$cuadro->evento('listado')->desactivar();
			$cuadro->evento('reporte')->desactivar(); 
			return;
		}
		foreach ($doc as $i) {
			if ($i['confirmado'] == 'S') {
				$i['imagen'] = toba_recurso::imagen_toba('tilde.gif', true);
			} else {
				$i['imagen'] = toba_recurso::imagen_toba('vacio.png', true);
			}
			$aux[] = $i;
			}
			$cuadro->set_datos($aux);
	}

	function evt__cuadro__seleccion($seleccion)
	{       
		$confirmado = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($seleccion['asignacion']); 
		if ($confirmado['confirmado'] != 'S') {
			$this->informar_msg("El docente no complet� su autoevalaci�n","error");
			return;   
		} 
		toba::memoria()->set_dato('asignacion',$seleccion['asignacion']);
		$this->relacion()->cargar($seleccion);
		$this->set_pantalla('pant_form');
	}

	function evt__cuadro__volver($datos)
	{
		$this->relacion()->resetear();
		$this->controlador->set_pantalla('pant_desempenio');
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
		$asignacion = toba::memoria()->get_dato('asignacion');  
		$datos_tabla = toba::consulta_php('co_evaluaciones')->get_evaluacion_tabla($asignacion);
		$datos = toba::consulta_php('co_evaluaciones')->get_evaluacion($asignacion);
		$evaluador = toba::memoria()->get_dato('persona');
		$datos['evaluador'] = $evaluador;
				
		$datos_docente = toba::consulta_php('co_autoevaluaciones')->get_ficha_de_docente_por_asignacion($asignacion);
		if ($datos_docente['ficha_docente'] == 'S') {
			$nombre = substr($datos_docente['ficha_docente_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos_docente['ficha_docente_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024);
			$datos['ficha_docente_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar ficha</a>";  
		}    
		$datos_docente_act = toba::consulta_php('co_autoevaluaciones')->get_informes_docente($asignacion);
		if ($datos_docente_act['informe_catedra'] == 'S') {
			$nombre = substr($datos_docente_act['informe_catedra_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos_docente_act['informe_catedra_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024);
			$datos['informe_catedra_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar informe de catedra</a>";      
		} 
		if ($datos_docente_act['programa'] == 'S') {
			$nombre = substr($datos_docente_act['programa_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos_docente_act['programa_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024);
			$datos['programa_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar programa</a>";      
		} 
		if ($datos_docente_act['informe_otros'] == 'S') {
			$nombre = substr($datos_docente_act['informe_otros_path'],19);
			$dir_tmp = toba::proyecto()->get_www_temp();
			exec("cp '". $datos_docente_act['informe_otros_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
			$temp_archivo = toba::proyecto()->get_www_temp($nombre);
			$tamanio = round(filesize($temp_archivo['path']) / 1024); 
			$datos['tipo_informe'] = $datos_docente_act['tipo_informe'];
			$datos['informe_otros_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo de informe</a>";      
		}             
		$form->set_datos($datos);
			
		if ($datos['confirmado'] == 'S')
			$form->evento('modificacion')->desactivar();
	}

	function evt__form__modificacion($datos)
	{
		if (($datos['calificacion'] == 'Insatisfactorio' or $datos['calificacion'] == 'Poco satisfactorio') and $datos['plan_de_mejora'] == 'N') {
			$this->informar_msg("Si la evaluaci�n NO es satistactoria se debe solicitar un plan de mejora","error");
			return;
		} 
		if ($datos['notificacion'] == 'S') {
			$this->informar_msg("La evaluaci�n ya fue notificada por el docente, no se puede modificar","error");
			return;
		} 
		if ($datos['confirmado'] == 'S' and $datos['calificacion'] == null) {
			$this->informar_msg("No se puede confirmar una evaluaci�n sin cargar la calificaci�n","error");
			return;
		}    
		if ($datos['calificacion'] == 'No se realizo' and $datos['observaciones'] == null) {
			$this->informar_msg("Si la actividad NO se realizo debe explicar el motivo en el campo observaciones","error");
			return;
		} 
		if ($datos['confirmado'] == 'N') {
			$this->informar_msg("Los datos se guardaron correctamente, pero la evaluaci�n a�n no fue confirmada","notificacion");
		} 
	
		$datos['fecha'] = date('Y-m-d');
		$asignacion = toba::memoria()->get_dato('asignacion');  
		$datos['asignacion'] = $asignacion;
		$this->tabla('evaluaciones')->set($datos);
		$this->relacion()->sincronizar();
		$this->relacion()->resetear();
		toba::memoria()->set_dato('asignacion',null); 
		
		if ($datos['confirmado'] == 'S') {
			$this->enviar_mail($asignacion);
		}         
		
		$this->set_pantalla('pant_inicial');        
	}   
	
	function evt__form__cancelar()
	{        
		$this->relacion()->resetear();
		toba::memoria()->set_dato('asignacion',null); 
		$this->set_pantalla('pant_inicial');
	}
	
	function enviar_mail($asignacion)
	{  
		$docente = toba::consulta_php('co_personas')->get_persona_por_asignacion($asignacion);
		
		if (isset($docente['mail'])) {
			$asunto = "Sistema de evaluacion de actividades FCE";
			$cuerpo_mail = '<p>'."A trav�s del presente correo le informamos que Ud. fue evaluado en una de sus actividades en el marco del SISTEMA DE EVALUACI�N DE ACTIVIDADES. ".
				" Para poder conocer la evaluaci�n deber� ingresar al sistema, dentro del apartado INFORME ANUAL DE DESEMPE�O, y hacer click en �Mis evaluaciones�. ".
				" All� se desplegara el listado de sus actividades y por cada una de ellas, al ingresar a trav�s del icono de la lupa, podr� acceder a la evaluaci�n y los comentarios realizados por el evaluador. ".
				" Al pie de la evaluaci�n, deber� notificarse de la misma, tildando el campo �notificaci�n�, contando con el espacio de �observaciones� para comentarios. ".
				'</p>';
			$mail = new toba_mail($docente['mail'], $asunto, $cuerpo_mail);
			$mail->set_html(true);
			//$mail->enviar();
		}
	}    
	
	function vista_jasperreports(toba_vista_jasperreports $report) 
	{    
		$tipo = toba::memoria()->get_parametro('param');
		
		if ($tipo == 'plan') {
			$asignacion = toba::memoria()->get_dato('asignacion');
			$report->set_parametro('asignacion','E',$asignacion);  
			$report->set_nombre_archivo('Plan de desarrollo');
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'plan_de_desarrollo.jasper';
		} else {
			if ($tipo == 'evaluar') {    
				$report->set_nombre_archivo('Reporte de evaluador');
				$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
				$path = $path_toba.'evaluaciones_por_evaluador_UNPATA_FCE.jasper'; 
			} else {
				$report->set_nombre_archivo('Reporte final evaluador');
				$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
				$path = $path_toba.'evaluador_final.jasper';                
			}
		}
		$evaluador = toba::usuario()->get_nombre();
		$report->set_parametro('evaluador','S',$evaluador);            
		$report->completar_con_datos();
	}    
}
?>