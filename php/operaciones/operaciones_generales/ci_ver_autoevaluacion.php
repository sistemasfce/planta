<?php
class ci_ver_autoevaluacion extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- form_no_resp -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_no_resp(planta_ei_formulario $form)
	{
		$asignacion = toba::memoria()->get_dato('asignacion'); 
		$datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);     
		$form->set_datos($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_resp --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_resp(planta_ei_formulario $form)
	{
		$asignacion = toba::memoria()->get_dato('asignacion'); 
		$datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);
		$form->set_datos($datos);
	}

	function conf()
	{
		$asignacion = toba::memoria()->get_dato('asignacion'); 
		$datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);
		
		if ($datos['resultado_resp'] == null) {
			// NO es responsable
			$this->set_pantalla('pant_no_resp');
			$this->pantalla()->tab('pant_responsable')->ocultar();
			$this->pantalla()->tab('pant_no_resp')->mostrar();
		} else {
			// Es responsable
			$this->pantalla()->tab('pant_responsable')->mostrar();
			$this->pantalla()->tab('pant_no_resp')->ocultar();            
		}
	}    
}
?>