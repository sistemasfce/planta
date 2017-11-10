<?php
class ci_datos_contacto extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$perfil = 1; // docentes
		$datos = toba::consulta_php('co_personas')->get_datos_contacto($perfil);
		$cuadro->set_datos($datos);        
	}

}
?>