<?php
class ci_puntajes_por_materia extends planta_ci
{
	protected $s__filtro;

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$cliente = toba::servicio_web_rest('guarani')->guzzle();
		
		$where = $this->dep('filtro')->get_sql_where();   
		$filtro = $this->dep('filtro')->get_datos();
		$ubicacion = $filtro['ubicacion']['valor'];
		
		if ($where == '1=1') 
			return;
		
		$actividades = toba::consulta_php('co_parametros')->get_actividades_catedras($where);
		foreach ($actividades as $act) {

			// CONSULTO CANTIDAD DE ALUMNOS DE LA MATERIA
			//-------------------------------------------------------------------------------
			$total = 0;
			$media = 0;  
			$suma_puntos = 0; 
			if ($act['codigo'] > 0) {
				for ($i = 1;$i < 6;$i++) {
					$anio = date('Y') - $i;
					$datosws['codigo'] = $act['codigo'];
					$datosws['ubicacion'] = $ubicacion;
					$datosws['anio'] = $anio;
					$request = $cliente->get('mediaactividad/' . 30366, array('body' => rest_encode($datosws)));
					$alumno = rest_decode($request->json());
						//$alumno = toba::consulta_php('co_guarani')->get_media_actividad($anio,$act['codigo'],$ubicacion);
					$total = $total + $alumno['media'];
				}
				if ($total > 0)
					$media = $total / 5;        
			}
			//-------------------------------------------------------------------------------
			
			$materia = " rtrim(actividades.descripcion) = '".trim($act['descripcion']). "' AND asignaciones.ubicacion = ".$ubicacion;
			$docentes = toba::consulta_php('co_asignaciones')->get_planta_docente($materia);
			foreach ($docentes as $doc) {
				$datos = toba::consulta_php('co_asignaciones')->get_puntajes_por_actividad($act['codigo'], $ubicacion, $doc['persona']);
				foreach($datos as $dat) {
					$dat['puntaje_dedicacion'] = $dat['puntaje_dedicacion_activos'] + $dat['puntaje_dedicacion_licencia'];
					$dat['puntaje_liquidacion'] = $dat['puntaje_liquidacion_activos'] + $dat['puntaje_liquidacion_licencia'];
					$dat['horas_designacion'] = $dat['suma_activas'] + $dat['horas_licencia'];
					$dat['porcentaje'] = $dat['horas_asignadas'] / $dat['horas_designacion'] * 100;
					$dat['puntaje_porcentaje'] = ($dat['porcentaje'] / 100) * $dat['puntaje_liquidacion'];
					$dat['puntos_utilizados'] = ($dat['porcentaje'] / 100) * $dat['puntaje_dedicacion'];
					$suma_puntos = $suma_puntos + $dat['puntos_utilizados'];
					$dat['media_alumnos'] = $media;
					$dat['descripcion_media'] = $dat['descripcion'] . ' - Media de alumnos: '.$media;
					$aux[] = $dat;
				} // fin foreach datos    
			} // fin foreach docente 
			foreach ($aux as $mat) {
				if ($suma_puntos > 0)
					$mat['alum_por_punto'] = round($media / $suma_puntos,2);
					$mat['descripcion_media'] = $mat['descripcion'] . ' - Media de alumnos: '.$media. ' - Alum. por punto: '. round($media / $suma_puntos,2);
				$completo[] = $mat;
			}
			$aux = array();
		} // fin foreach materia
		$cuadro->set_datos($completo);
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