<?php
class ci_excepciones_a_periodos extends planta_ci
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
		$datos = toba::consulta_php('co_parametros')->get_excepciones_evaluaciones();
		$cuadro->set_datos($datos);
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->relacion()->cargar($seleccion);
	}
	
	//-----------------------------------------------------------------------------------
	//---- form -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------   
	function conf__form(planta_ei_formulario $form)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('excepciones_evaluaciones')->get();            
			$form->set_datos($datos);
		}
	}
		
	function evt__form__alta($datos)
	{
		if ($datos['habilitado'] == 'S' and $datos['bloqueado'] == 'S') {
			toba::notificacion()->agregar('La excepci�n no puede ser habilitado y bloqueado al mismo tiempo');
			return;
		}
		$this->tabla('excepciones_evaluaciones')->set($datos);
		$this->relacion()->sincronizar();
		$this->relacion()->resetear();
	}

	function evt__form__baja()
	{
		try {
			$this->relacion()->eliminar_todo();
		} catch (toba_error $e) {
			toba::notificacion()->agregar('No es posible eliminar el registro.');
		}
		$this->relacion()->resetear();
	}

	function evt__form__modificacion($datos)
	{
		if ($datos['habilitado'] == 'S' and $datos['bloqueado'] == 'S') {
			toba::notificacion()->agregar('La excepci�n no puede ser habilitado y bloqueado al mismo tiempo');           
			return;
		}
		$this->tabla('excepciones_evaluaciones')->set($datos);
		$this->relacion()->sincronizar();
		$this->relacion()->resetear();
	}

	function evt__form__cancelar()
	{
		$this->relacion()->resetear();
	}    
}
?>