<?php

class co_evaluaciones
{
    function get_evaluaciones_de_persona_por_ciclo($persona,$ciclo)
    {
	$sql = "SELECT 
			evaluaciones.evaluacion,
			personas.apellido || ', ' || personas.nombres as nombre_completo,
			per2.apellido || ', ' || per2.nombres as evaluador_nombre,
			ubicaciones.codigo as ubicacion_desc,
			dimensiones.descripcion as dimension_desc,
			actividades.descripcion as actividad_desc,
			categorias.descripcion as rol_desc,
			departamentos.descripcion as departamento_desc,
		      	evaluaciones.asignacion,
			evaluaciones.calificacion,
			evaluaciones.confirmado,
                        evaluaciones.ciclo_lectivo,
			evaluaciones.notificacion
		FROM evaluaciones LEFT OUTER JOIN personas as per2 ON (evaluaciones.evaluador = per2.persona),
			personas, asignaciones
			LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
		WHERE	evaluaciones.asignacion = asignaciones.asignacion  
			AND asignaciones.persona = personas.persona
			AND asignaciones.carrera_academica = 'S'
			AND actividades.se_evalua = 'S'
			AND asignaciones.persona = $persona AND evaluaciones.ciclo_lectivo = $ciclo
			AND evaluaciones.estado = 1
		";
	return toba::db()->consultar($sql);
    }

    function get_evaluacion_tabla($asignacion)
    {
	$sql = "SELECT *
		FROM evaluaciones
		WHERE asignacion = $asignacion
		";
	return toba::db()->consultar_fila($sql);

    }

    function get_evaluacion($asignacion)
    {
	$sql = "SELECT  evaluaciones.*,
                        ubicaciones.descripcion as ubicacion_desc,
                        dimensiones.descripcion as dimension_desc,
                        actividades.descripcion as actividad_desc,
                        categorias.descripcion as rol_desc,
			personas.apellido || ', ' || personas.nombres as nombre_completo
                FROM evaluaciones, asignaciones
                        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
                        LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
                        LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
                        LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
                WHERE   evaluaciones.asignacion = asignaciones.asignacion  
			AND asignaciones.asignacion = $asignacion

		";
	return toba::db()->consultar_fila($sql);
    }

//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------

    function get_docentes_a_cargo($persona,$ciclo,$pendientes=null)
    {
	if ($ciclo == date('Y'))	
		$ciclo_actual = " AND asignaciones.estado = 1 AND asig2.estado = 1";
	else
		$ciclo_actual = "";

	if (isset($pendientes))
		$where = " AND confirmado = 'N'";
	else
		$where = "";
	$sql = "-- obtener los docentes de una actividad en donde soy responsable
		SELECT 	
			'Soy responsable de la actividad' as motivo,
			asig2.asignacion,
			personas.persona as persona_evaluado,
			personas.apellido || ', ' || nombres as evaluado_nombre_completo,
			actividades.descripcion as actividad_desc,
			ubicaciones.codigo as ubicacion_desc,
			categorias.descripcion as rol_desc,
			dimensiones.codigo as dimension_desc,
			departamentos.descripcion as departamento_desc,
			evaluaciones.calificacion,
			evaluaciones.confirmado,
			evaluaciones.notificacion
		FROM 	asignaciones, 
			asignaciones as asig2 LEFT OUTER JOIN dimensiones ON (asig2.dimension = dimensiones.dimension)
			LEFT OUTER JOIN designaciones ON (asig2.designacion = designaciones.designacion)
			LEFT OUTER JOIN categorias ON (asig2.rol = categorias.categoria) 
			LEFT OUTER JOIN departamentos ON (asig2.departamento = departamentos.departamento)
			LEFT OUTER JOIN evaluaciones ON (asig2.asignacion = evaluaciones.asignacion),
			personas,
			ubicaciones, 
			actividades
		WHERE asignaciones.persona = $persona
			AND evaluaciones.estado = 1 
			AND asignaciones.responsable = 'S'
			AND asignaciones.ciclo_lectivo = '$ciclo'
			AND asignaciones.actividad = asig2.actividad
			
			AND asig2.ubicacion = asignaciones.ubicacion
			AND asig2.ubicacion = ubicaciones.ubicacion
			AND asig2.ciclo_lectivo = '$ciclo'
			AND asignaciones.persona <> asig2.persona
			AND asig2.persona = personas.persona
			AND asig2.actividad = actividades.actividad
			AND designaciones.caracter = 2 -- regulares
			AND evaluaciones.ciclo_lectivo = '$ciclo'
			$where
			$ciclo_actual
		ORDER BY actividad_desc, ubicacion_desc, evaluado_nombre_completo
		";
	return toba::db()->consultar($sql);
    }

    function get_actividades_a_evaluar($persona,$ciclo,$pendientes=null)
    {
	if ($ciclo == date('Y'))    
                $ciclo_actual = " AND asignaciones.estado = 1 AND asig2.estado = 1";
        else
                $ciclo_actual = ""; 

	if (isset($pendientes))
		$where = " AND confirmado = 'N'";
	else
		$where = "";
	$sql = "SELECT
                        'mi actividad evalua al resp' as motivo,
                        asig2.asignacion,
                        personas.persona as persona_evaluado,
                        personas.apellido || ', ' || nombres as evaluado_nombre_completo,
                        act2.descripcion as actividad_desc,
                        ubicaciones.codigo as ubicacion_desc,
                        categorias.descripcion as rol_desc,
                        dimensiones.codigo as dimension_desc,
			departamentos.descripcion as departamento_desc,
                        evaluaciones.calificacion,
                        evaluaciones.confirmado,
			evaluaciones.notificacion
		FROM asignaciones, 
			actividades LEFT OUTER JOIN actividades_a_evaluar ON (actividades.actividad = actividades_a_evaluar.actividad_evaluador) 
			LEFT OUTER JOIN actividades as act2 ON (actividades_a_evaluar.actividad_evaluado = act2.actividad), 
			asignaciones as asig2 
			LEFT OUTER JOIN dimensiones ON (asig2.dimension = dimensiones.dimension) 
			LEFT OUTER JOIN categorias ON (asig2.rol = categorias.categoria) 
			LEFT OUTER JOIN departamentos ON (asig2.departamento = departamentos.departamento)
			LEFT OUTER JOIN evaluaciones ON (asig2.asignacion = evaluaciones.asignacion), 
			personas, 
			ubicaciones 

		WHERE 	asignaciones.actividad = actividades.actividad
			AND asignaciones.persona = $persona
			AND asignaciones.ciclo_lectivo = $ciclo
			AND asignaciones.responsable = 'S'
		        AND asignaciones.ubicacion = actividades_a_evaluar.ubicacion_evaluador
            		AND asig2.ubicacion = actividades_a_evaluar.ubicacion_evaluado
            		AND act2.actividad = asig2.actividad
            		AND asig2.persona = personas.persona
            		AND asig2.ubicacion = ubicaciones.ubicacion
            		AND asig2.responsable = actividades_a_evaluar.responsable
			AND asig2.ciclo_lectivo = $ciclo
			$where
			$ciclo_actual
		";
	return toba::db()->consultar($sql);
    }
 
    function get_ambitos_a_evaluar($persona,$ciclo,$pendientes=null)
    {
        if ($ciclo == date('Y'))
                $ciclo_actual = " AND asignaciones.estado = 1 AND asig2.estado = 1";
        else
                $ciclo_actual = "";

	if (isset($pendientes))
		$where = " AND confirmado = 'N'";
	else
		$where = "";
	$sql = "
	SELECT
            'evaluo al resp por ambito' as motivo,
            asig2.asignacion,
            personas.persona as persona_evaluado,
            personas.apellido || ', ' || nombres as evaluado_nombre_completo,
            act2.descripcion as actividad_desc,
            ubicaciones.codigo as ubicacion_desc,
            categorias.descripcion as rol_desc,
            dimensiones.codigo as dimension_desc,
	    departamentos.descripcion as departamento_desc,
            evaluaciones.calificacion,
            evaluaciones.confirmado,
		evaluaciones.notificacion
        FROM asignaciones, 
            actividades LEFT OUTER JOIN ambitos_a_evaluar ON (actividades.actividad = ambitos_a_evaluar.actividad_evaluador) 
            LEFT OUTER JOIN actividades as act2 ON (ambitos_a_evaluar.ambito_evaluado = act2.ambito), 
            asignaciones as asig2 
            LEFT OUTER JOIN dimensiones ON (asig2.dimension = dimensiones.dimension) 
            LEFT OUTER JOIN categorias ON (asig2.rol = categorias.categoria) 
            LEFT OUTER JOIN departamentos ON (asig2.departamento = departamentos.departamento) 
            LEFT OUTER JOIN evaluaciones ON (asig2.asignacion = evaluaciones.asignacion), 
            personas, 
            ubicaciones 
        WHERE     asignaciones.actividad = actividades.actividad
            AND asignaciones.persona = $persona
            AND asignaciones.ciclo_lectivo = $ciclo
            AND asignaciones.responsable = 'S'
            AND asignaciones.ubicacion = ambitos_a_evaluar.ubicacion_evaluador
            AND asig2.ubicacion = ambitos_a_evaluar.ubicacion_evaluado
            AND act2.actividad = asig2.actividad
            AND asig2.persona = personas.persona
            AND asig2.ubicacion = ubicaciones.ubicacion
            AND asig2.responsable = ambitos_a_evaluar.responsable
	AND asig2.ciclo_lectivo = $ciclo
	    	$where
		$ciclo_actual
		";
	return toba::db()->consultar($sql);
    }

//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------

    function get_evaluaciones_pendientes($where='1=1')
    {
	$sql = "	
		SELECT personas.apellido || ', ' || personas.nombres as evaluado_nombre_completo,
                pp.apellido || ', ' || pp.nombres as evaluador_nombre_completo,
                ubicaciones.codigo as ubicacion_desc,
                dimensiones.codigo as dimension_desc,
		actividades.descripcion as actividad_desc
		FROM evaluaciones LEFT OUTER JOIN personas as pp ON (evaluaciones.evaluador = pp.persona),
                        personas, 
                        asignaciones
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
                        LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
                        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
                        
		WHERE 	
			evaluaciones.asignacion = asignaciones.asignacion
			AND asignaciones.persona = personas.persona
			AND evaluaciones.confirmado = 'N' AND $where 
                            AND asignaciones.actividad <> 347
		ORDER BY evaluado_nombre_completo
		"; 
	return toba::db()->consultar($sql);
    }

    function get_resumen_evaluaciones($ciclo)
    {
	$sql = "
		SELECT  autoevaluaciones_por_act.persona,
			personas.apellido || ', ' || personas.nombres as nombre_completo,
                        
			(SELECT confirmado FROM autoevaluaciones WHERE ciclo_lectivo = $ciclo 
				AND autoevaluaciones.persona = autoevaluaciones_por_act.persona) as ficha_docente,
			(SELECT COUNT (autoevaluacion_por_act) FROM autoevaluaciones_por_act as apa 
				LEFT OUTER JOIN asignaciones ON (apa.asignacion = asignaciones.asignacion)
                         	LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
				WHERE apa.confirmado = 'S' 
				AND apa.persona = autoevaluaciones_por_act.persona
				AND apa.estado = 1 AND apa.ciclo_lectivo = $ciclo
				AND actividades.se_evalua = 'S') as autoeval_realizadas,
			count (autoevaluacion_por_act) as autoeval_total,
                        
			(SELECT COUNT (evaluacion) FROM evaluaciones 
				LEFT OUTER JOIN asignaciones ON (evaluaciones.asignacion = asignaciones.asignacion)
				LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
				WHERE evaluaciones.persona = autoevaluaciones_por_act.persona
				AND evaluaciones.ciclo_lectivo = $ciclo AND evaluaciones.estado = 1 AND confirmado = 'S' AND actividades.se_evalua = 'S'
			) as evaluado_realizadas,
			(SELECT COUNT (evaluacion) FROM evaluaciones 
				LEFT OUTER JOIN asignaciones ON (evaluaciones.asignacion = asignaciones.asignacion)
				LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
				WHERE evaluaciones.persona = autoevaluaciones_por_act.persona
				AND evaluaciones.ciclo_lectivo = $ciclo AND evaluaciones.estado = 1 AND actividades.se_evalua = 'S'
			) as evaluado_total,
			(SELECT COUNT (evaluacion) FROM evaluaciones 
				LEFT OUTER JOIN asignaciones ON (evaluaciones.asignacion = asignaciones.asignacion)
				LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
				WHERE evaluaciones.persona = autoevaluaciones_por_act.persona
				AND evaluaciones.ciclo_lectivo = $ciclo AND evaluaciones.estado = 1 AND actividades.se_evalua = 'S' AND notificacion = 'S'
			) as evaluado_notificado,
                        
			(SELECT COUNT (evaluacion) FROM evaluaciones 
				LEFT OUTER JOIN asignaciones ON (evaluaciones.asignacion = asignaciones.asignacion)
				LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
				WHERE evaluaciones.evaluador = autoevaluaciones_por_act.persona
				AND evaluaciones.ciclo_lectivo = $ciclo AND evaluaciones.estado = 1 
				AND confirmado = 'S' AND actividades.se_evalua = 'S'
			) as evaluador_realizadas,	
			(SELECT COUNT (*) FROM vista_evaluador
                		WHERE vista_evaluador.persona = autoevaluaciones_por_act.persona
				AND asignaciones_ciclo_lectivo = $ciclo
				AND asig2_ciclo_lectivo = $ciclo
				AND evaluaciones_ciclo_lectivo = $ciclo
		        ) as evaluador_total, 
	  		(SELECT COUNT (evaluacion) FROM evaluaciones 
				LEFT OUTER JOIN asignaciones ON (evaluaciones.asignacion = asignaciones.asignacion)
				LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
				WHERE evaluaciones.evaluador = autoevaluaciones_por_act.persona
				AND evaluaciones.ciclo_lectivo = $ciclo AND evaluaciones.estado = 1 AND actividades.se_evalua = 'S' AND notificacion = 'S'
			) as evaluador_notificado  
                        
		FROM autoevaluaciones_por_act 
			LEFT OUTER JOIN personas ON (autoevaluaciones_por_act.persona = personas.persona)
			LEFT OUTER JOIN asignaciones ON (autoevaluaciones_por_act.asignacion = asignaciones.asignacion)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
		WHERE 	
			 autoevaluaciones_por_act.estado = 1
			AND autoevaluaciones_por_act.ciclo_lectivo = $ciclo
			AND actividades.se_evalua = 'S'
			AND personas.estado_docente = 1
		GROUP BY autoevaluaciones_por_act.persona, nombre_completo
		ORDER BY nombre_completo
		";
	return toba::db()->consultar($sql);
    }

}

?>
