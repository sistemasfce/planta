<?php
class ci_desempenio extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- form_desemp ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__form_desemp__evaluador($datos)
	{
		$this->controlador()->set_pantalla('pant_evaluar');    
	}

	function evt__form_desemp__mis_evaluaciones($datos)
	{
		$this->controlador()->set_pantalla('pant_miseval');
	}    
}
?>