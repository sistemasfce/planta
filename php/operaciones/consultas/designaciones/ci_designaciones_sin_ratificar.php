<?php
class ci_designaciones_sin_ratificar extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$datos = toba::consulta_php('co_designaciones')->get_designaciones_sin_ratificar();
		$cuadro->set_datos($datos);
	}    
}
?>