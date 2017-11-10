<?php
class ci_personas_titulos extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$datos = toba::consulta_php('co_personas')->get_personas_titulos();
		$cuadro->set_datos($datos);
	}
}
?>