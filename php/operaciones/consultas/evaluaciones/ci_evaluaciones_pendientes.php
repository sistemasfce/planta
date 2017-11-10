<?php
class ci_evaluaciones_pendientes extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		if ($where == '1=1')
			return;
		$datos_filtro = $this->dep('filtro')->get_datos();
		$ciclo = $datos_filtro['ciclo_lectivo']['valor'];
		$evaluadores = toba::consulta_php('co_docentes')->get_docentes();   
		foreach ($evaluadores as $ev) {
			$persona = $ev['persona'];
			// busco los docentes que tengo a cargo, no hace falta pasar el ciclo lectivo
			// porque solo busco asignaciones activas
			$docentes_a_cargo = toba::consulta_php('co_evaluaciones')->get_docentes_a_cargo($persona,$ciclo,'S');   
			// buscar para todas mis actividades donde soy responsable
			// si la actividad esta en la tabla actividades_a_evaluar
			// buscar los docentes responsables de esas actividades
			$actividades_a_evaluar = toba::consulta_php('co_evaluaciones')->get_actividades_a_evaluar($persona,$ciclo,'S');
			$doc = array_merge($docentes_a_cargo,$actividades_a_evaluar);
			// buscar para todas mis actividades donde soy responsable
			// si la actividad esta en la tabla ambitos_a_evaluar
			// buscar los docentes responsables de esas actividades
			$ambitos_a_evaluar = toba::consulta_php('co_evaluaciones')->get_ambitos_a_evaluar($persona,$ciclo,'S');
			$doc = array_merge($doc,$ambitos_a_evaluar);
			
			foreach ($doc as $evaluado) {
				$evaluado['evaluador_nombre_completo'] = $ev['nombre_completo'];
				$aux[] = $evaluado;
			}  
		}

		$cuadro->set_titulo('Docentes que no tienen confirmada su evaluación');
		$cuadro->set_datos($aux);        
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
}
?>