<?php
class ci_docentes_a_definir extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro_horas -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$datos = toba::consulta_php('co_docentes')->get_docentes_a_definir();
		$completo = array();
		foreach ($datos as $dat) {
			$aux = $dat;
			$aux['horas_neto'] = $dat['horas_desig'] - $dat['horas_licenciadas'];
			$aux['horas_utilizadas'] = $dat['horas_asignadas'];
			$aux['horas_sin_asignar'] = $aux['horas_neto'] - $aux['horas_utilizadas']; 
			#if ($aux['horas_sin_asignar'] > 0 or $aux['a_definir'] > 0)
				$completo[] = $aux;
		} 
		$cuadro->set_datos($completo);        
	}    
}
?>