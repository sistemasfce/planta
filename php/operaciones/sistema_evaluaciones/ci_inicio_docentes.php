<?php
class ci_inicio_docentes extends planta_ci
{   
	
	protected $s__filtro;
	
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
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
		$perfil = toba::usuario()->get_perfiles_funcionales();
		if ($perfil[0] != 'admin' and $perfil[0] != 'usuario') {
			$documento = toba::usuario()->get_id();
			$this->pantalla('pant_inicial')->eliminar_dep('filtro');
			$persona = toba::consulta_php('co_personas')->get_id($documento);
			toba::memoria()->set_dato('persona',$persona['persona']);
			toba::memoria()->set_dato('usuario','docente');
			$hoy = strtotime(date('Y-m-d'));
			
			$fechas_autoeval = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_VENCE');
			$desde = strtotime(date($fechas_autoeval['valor_fecha_desde']));
			$hasta = strtotime(date($fechas_autoeval['valor_fecha_hasta'])); 
			// busco excepciones para la persona
			$excepciones_autoeval = toba::consulta_php('co_parametros')->get_excepciones_persona($persona['persona'],date('Y-m-d'),'PAR_AUTOEVAL_VENCE');
			
			if ($hoy >= $desde and $hoy <= $hasta) {
				// estoy dentro de las fechas de autoevaluacion
				// miro si la persona esta bloqueada en este periodo
				if ($excepciones_autoeval['bloqueado'] == 'S') {
					$this->dep('form')->evento('autoevaluacion')->desactivar();
				}
			} else {
				if ($excepciones_autoeval['habilitado'] == 'S') {
					// la persona esta habilitada, dejo el boton disponible
				} else {
					$this->dep('form')->evento('autoevaluacion')->desactivar();
				}
			}
			
			$fechas_eval = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_EVAL_VENCE');
			$desde = strtotime(date($fechas_eval['valor_fecha_desde']));
			$hasta = strtotime(date($fechas_eval['valor_fecha_hasta']));     
			$excepciones_eval = toba::consulta_php('co_parametros')->get_excepciones_persona($persona['persona'],date('Y-m-d'),'PAR_EVAL_VENCE');            
			if ($hoy >= $desde and $hoy <= $hasta) {
				// estoy dentro de las fechas de evaluacion
				// miro si la persona esta bloqueada en este periodo
				if ($excepciones_eval['bloqueado'] == 'S') {
					$this->dep('form')->evento('desempenio')->desactivar();
				}
			} else {
				if ($excepciones_eval['habilitado'] == 'S') {
					// la persona esta habilitada, dejo el boton disponible    
				} else {
					$this->dep('form')->evento('desempenio')->desactivar();
				}
			}
		} else {
			toba::memoria()->set_dato('usuario','admin');
		}
	}   

        function conf__cuadro(planta_ei_cuadro $cuadro)
        {
            $persona = toba::memoria()->get_dato('persona');
            $datos_aux = array();
            $ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
            if (isset($persona)) {
                $datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluaciones_a_controlar($persona,$ciclo['valor_num']);
                
                foreach ($datos as $dat) {
                    $aux = $dat;
                    if ($dat['path_ficha'] != '') {
                          // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
                        $nombre = substr($dat['path_ficha'],19);
                        $dir_tmp = toba::proyecto()->get_www_temp();
                        exec("cp '". $dat['path_ficha']. "' '" .$dir_tmp['path']."/".$nombre."'");
                        $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                        $tamanio = round(filesize($temp_archivo['path']) / 1024);
                        $aux['archivo'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                        #$datos['archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';                          
                    }
                    $datos_aux[] = $aux;
                }
                $cuadro->set_datos($datos_aux);                
            }
        }
		
	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{

		$usuario = toba::memoria()->get_dato('usuario');
		if ($usuario == 'admin' or $perfil[0] == 'usuario') {
			if (isset($this->s__filtro)) {
				$ciclo = $this->s__filtro['ciclo_lectivo']['valor'];
				toba::memoria()->set_dato('ciclo',$ciclo);
				$persona = $this->s__filtro['persona']['valor'];
				toba::memoria()->set_dato('persona',$persona);            
				$where = 'persona = '.$persona;
				$nombre = toba::consulta_php('co_personas')->get_persona_nombre($where);
				toba::memoria()->set_dato('nombre',$nombre['nombre_completo']);
				$datos['nombre'] = $nombre['nombre_completo'];
				$form->evento('cambiar_clave')->desactivar();
			} else {
				$form->evento('autoevaluacion')->desactivar();
				$form->evento('desempenio')->desactivar();
				$form->evento('cambiar_clave')->desactivar();
				$form->evento('historicos')->desactivar();
			}
		} else {
			$persona = toba::memoria()->get_dato('persona');            
			$where = 'persona = '.$persona;
			$ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
			toba::memoria()->set_dato('ciclo',$ciclo['valor_num']); 
			$nombre = toba::consulta_php('co_personas')->get_persona_nombre($where);
			toba::memoria()->set_dato('nombre',$nombre['nombre_completo']);
			$datos['nombre'] = $nombre['nombre_completo'];
		}
		toba::memoria()->set_dato('path',null);
		$form->set_datos($datos);
	}

	function evt__form__desempenio($datos)
	{
		$this->set_pantalla('pant_desempenio');
	}    

	function evt__form__autoevaluacion($datos)
	{
		$this->set_pantalla('pant_autoeval');
	}  

	function evt__form__cambiar_clave($datos)
	{
		$this->set_pantalla('pant_clave');
	}      
	
	function evt__form__historicos($datos)
	{
		$this->set_pantalla('pant_historicos');
	}            
		
	function evt__volver()
	{
		$this->dep('autoevaluacion')->dep('relacion')->resetear();
		$this->set_pantalla('pant_inicial');          
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
	//-----------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function vista_jasperreports(toba_vista_jasperreports $report) 
	{       
		$tipo = toba::memoria()->get_parametro('param');
		
		if ($tipo == 'plan') {
			$report->set_nombre_archivo('Plan de desarrollo.pdf');
			$funcion = toba::memoria()->get_dato('jasper');
			$report->set_parametro('funcion','E',$funcion);  
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'plan_de_desarrollo.jasper';
			$report->set_path_reporte($path);
		} 
		if ($tipo == 'evaluado') {    
			$report->set_nombre_archivo('Reporte de evaluado.pdf');
			$persona = toba::memoria()->get_dato('persona');
			$report->set_parametro('persona','E',$persona); 
			$ciclo = toba::memoria()->get_dato('ciclo');
			$report->set_parametro('ciclo','E',$ciclo);                         
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'mis_evaluaciones.jasper';
			$report->set_path_reporte($path);
		}
		if ($tipo == 'evaluado_final') {
			$report->set_nombre_archivo('Reporte final de evaluado.pdf');
			$persona = toba::memoria()->get_dato('persona');
			$report->set_parametro('persona','E',$persona); 
			$ciclo = toba::memoria()->get_dato('ciclo');
			$report->set_parametro('ciclo','E',$ciclo); 
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'mis_evaluaciones_final.jasper';
			$report->set_path_reporte($path);
		}
		if ($tipo == 'evaluar') {    
			$report->set_nombre_archivo('Reporte de evaluador.pdf');
			$persona = toba::memoria()->get_dato('persona');
			$report->set_parametro('evaluador','E',$persona); 
			$ciclo = toba::memoria()->get_dato('ciclo');
			$report->set_parametro('ciclo','E',$ciclo); 
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'evaluador.jasper';
			$report->set_path_reporte($path);
		}
		if ($tipo == 'evaluar_final') {
			$report->set_nombre_archivo('Reporte final de evaluador.pdf');
			$persona = toba::memoria()->get_dato('persona');
			$report->set_parametro('evaluador','E',$persona); 
			$ciclo = toba::memoria()->get_dato('ciclo');
			$report->set_parametro('ciclo','E',$ciclo); 
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'evaluador_final.jasper';
			$report->set_path_reporte($path);
		}    
		if ($tipo == 'autoevaluaciones_final') {
			$report->set_nombre_archivo('Reporte de autoevaluaciones.pdf');
			$persona = toba::memoria()->get_dato('persona');
			$report->set_parametro('persona','E',$persona); 
			$ciclo = toba::memoria()->get_dato('ciclo');
			$report->set_parametro('ciclo','E',$ciclo); 
			$path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
			$path = $path_toba.'mis_autoevaluaciones.jasper';
			$report->set_path_reporte($path);
		}                   
		$report->completar_con_datos();   
	}

}
?>