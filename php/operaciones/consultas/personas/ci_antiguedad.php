<?php

class ci_antiguedad extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$datos = toba::consulta_php('co_personas')->get_antiguedad();
		$cuadro->set_datos($datos);        
	}

}

