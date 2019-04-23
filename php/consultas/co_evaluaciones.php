<?php

class co_evaluaciones
{
    function get_evaluaciones($where)
    {
        $sql = "SELECT 	asignaciones.*,
			personas.apellido || ', ' || nombres as nombre_completo,
                        ubicaciones.codigo as ubicacion_desc,
			dimensiones.codigo as dimension_desc,
			actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
			categorias.descripcion as rol_desc,
                        estados.descripcion as estado_desc
		FROM 	asignaciones LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
                        LEFT OUTER JOIN estados ON (asignaciones.eval_estado = estados.estado)
                        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
		WHERE   $where
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }
    
    
    function get_evaluaciones_de_persona_por_ciclo($persona,$ciclo)
    {
	$sql = "SELECT 
			personas.apellido || ', ' || personas.nombres as nombre_completo,
			per2.apellido || ', ' || per2.nombres as evaluador_nombre,
			ubicaciones.codigo as ubicacion_desc,
			dimensiones.descripcion as dimension_desc,
			actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
			categorias.descripcion as rol_desc,
			departamentos.descripcion as departamento_desc,
		      	asignaciones.asignacion,
                        CASE WHEN asignaciones.eval_confirmado = 'S' THEN
			asignaciones.eval_calificacion
                        else '' END as eval_calificacion,
			asignaciones.eval_confirmado,
                        asignaciones.ciclo_lectivo,
			asignaciones.eval_notificacion
		FROM asignaciones LEFT OUTER JOIN personas as per2 ON (asignaciones.eval_evaluador = per2.persona)
			LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
			LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
		WHERE	
			
			actividades.se_evalua = 'S'
			AND asignaciones.persona = $persona AND asignaciones.ciclo_lectivo = $ciclo
			AND asignaciones.eval_estado = 1
		";
	return toba::db()->consultar($sql);
    }

    function get_evaluaciones_por_fecha($where)
    {
        $fecha_confirma = str_replace('fecha','eval_confirmado_fecha',$where);
        $fecha_sube = str_replace('fecha','eval_calificacion_fecha',$where);
        $fecha_noti = str_replace('fecha','eval_notificacion_fecha',$where);
        
        $sql = "SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Evaluación fue confirmada' as accion,
                        actividades.descripcion as actividad_desc,
                        eval_confirmado_fecha as fecha,
                        ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc
                FROM asignaciones LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                            LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN ubicaciones ON asignaciones.ubicacion = ubicaciones.ubicacion
                            LEFT OUTER JOIN dimensiones ON asignaciones.dimension = dimensiones.dimension
                WHERE $fecha_confirma
                
                UNION

                SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Calificaron su evaluación' as accion,
                        actividades.descripcion as actividad_desc,
                        eval_calificacion_fecha as fecha,
                        ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc
                FROM asignaciones LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                            LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN ubicaciones ON asignaciones.ubicacion = ubicaciones.ubicacion
                            LEFT OUTER JOIN dimensiones ON asignaciones.dimension = dimensiones.dimension
                WHERE $fecha_sube

                UNION

                SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Notificó su evaluación' as accion,
                        actividades.descripcion as actividad_desc,
                        eval_notificacion_fecha as fecha,
                        ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc
                FROM asignaciones LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                            LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN ubicaciones ON asignaciones.ubicacion = ubicaciones.ubicacion
                            LEFT OUTER JOIN dimensiones ON asignaciones.dimension = dimensiones.dimension
                WHERE $fecha_noti
                    
             ORDER BY nombre_completo";
        return toba::db()->consultar($sql);
    }    
    
    function get_evaluacion_tabla($asignacion)
    {
	$sql = "SELECT *
		FROM asignaciones
		WHERE asignacion = $asignacion
		";
	return toba::db()->consultar_fila($sql);

    }

    function get_evaluacion($asignacion)
    {
	$sql = "SELECT  asignaciones.*,
                        ubicaciones.descripcion as ubicacion_desc,
                        dimensiones.descripcion as dimension_desc,
                        actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
                        categorias.descripcion as rol_desc,
			personas.apellido || ', ' || personas.nombres as nombre_completo
                FROM asignaciones
                        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
                        LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
                        LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
                        LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
                WHERE   asignaciones.asignacion = $asignacion

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
		SELECT 	DISTINCT 
			'Soy responsable de la actividad' as motivo,
			asig2.asignacion,
			personas.persona as persona_evaluado,
			personas.apellido || ', ' || nombres as evaluado_nombre_completo,
			actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
			ubicaciones.codigo as ubicacion_desc,
			categorias.descripcion as rol_desc,
			dimensiones.codigo as dimension_desc,
			departamentos.descripcion as departamento_desc,
			asig2.eval_calificacion,
			asig2.eval_confirmado,
			asig2.eval_notificacion
		FROM 	asignaciones, 
			asignaciones as asig2 LEFT OUTER JOIN dimensiones ON (asig2.dimension = dimensiones.dimension)
			LEFT OUTER JOIN designaciones ON (asig2.designacion = designaciones.designacion)
			LEFT OUTER JOIN categorias ON (asig2.rol = categorias.categoria) 
			LEFT OUTER JOIN departamentos ON (asig2.departamento = departamentos.departamento),
			personas,
			ubicaciones, 
			actividades
		WHERE asignaciones.persona = $persona
			AND asig2.eval_estado = 1 
                        AND asignaciones.eval_estado = 1
			AND asignaciones.responsable = 'S'
                        --AND actividades.se_evalua = 'S'
			AND asignaciones.ciclo_lectivo = '$ciclo'
			AND asignaciones.actividad = asig2.actividad
			AND asig2.ubicacion = asignaciones.ubicacion
			AND asig2.ubicacion = ubicaciones.ubicacion
			AND asig2.ciclo_lectivo = '$ciclo'
			AND asignaciones.persona <> asig2.persona
			AND asig2.persona = personas.persona
			AND asig2.actividad = actividades.actividad
                        AND asignaciones.persona <> asig2.persona
            --            AND (asignaciones.fecha_desde < '$ciclo-11-01' AND asignaciones.dimension <> 4)
			$where
			$ciclo_actual
		ORDER BY actividad_desc, ubicacion_desc, evaluado_nombre_completo
		";
	return toba::db()->consultar($sql);
    }

//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
    
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
	$sql = "SELECT DISTINCT
                        'mi actividad evalua al resp' as motivo,
                        asig2.asignacion,
                        personas.persona as persona_evaluado,
                        personas.apellido || ', ' || nombres as evaluado_nombre_completo,
                        act2.descripcion as actividad_desc,
                        act2.nombre as actividad_nombre,
                        ubicaciones.codigo as ubicacion_desc,
                        categorias.descripcion as rol_desc,
                        dimensiones.codigo as dimension_desc,
			departamentos.descripcion as departamento_desc,
                        asig2.eval_calificacion,
                        asig2.eval_confirmado,
			asig2.eval_notificacion
		FROM asignaciones, 
			actividades LEFT OUTER JOIN actividades_a_evaluar ON (actividades.actividad = actividades_a_evaluar.actividad_evaluador) 
			LEFT OUTER JOIN actividades as act2 ON (actividades_a_evaluar.actividad_evaluado = act2.actividad), 
			asignaciones as asig2 
			LEFT OUTER JOIN dimensiones ON (asig2.dimension = dimensiones.dimension) 
			LEFT OUTER JOIN categorias ON (asig2.rol = categorias.categoria) 
			LEFT OUTER JOIN departamentos ON (asig2.departamento = departamentos.departamento), 
			personas, 
			ubicaciones 

		WHERE 	asignaciones.actividad = actividades.actividad
                        AND asig2.eval_estado = 1 
			AND asignaciones.persona = $persona
			AND asignaciones.ciclo_lectivo = $ciclo
			AND asignaciones.responsable = 'S'
                        --AND actividades.se_evalua = 'S'
		        AND asignaciones.ubicacion = actividades_a_evaluar.ubicacion_evaluador
            		AND asig2.ubicacion = actividades_a_evaluar.ubicacion_evaluado
            		AND act2.actividad = asig2.actividad
            		AND asig2.persona = personas.persona
            		AND asig2.ubicacion = ubicaciones.ubicacion
            		AND asig2.responsable = actividades_a_evaluar.responsable
			AND asig2.ciclo_lectivo = $ciclo
                        AND asignaciones.persona <> asig2.persona
                --        AND ( (asig2.fecha_desde < '$ciclo-11-01' AND asig2.dimension = 4) or (asig2.fecha_desde < '$ciclo-12-01' AND asig2.dimension <> 4) )
                --        AND asignaciones.fecha_desde < '$ciclo-11-01'
                        $where
			$ciclo_actual
		";
	return toba::db()->consultar($sql);
    }

//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
    
    function get_actividades_a_evaluar_directores($persona,$ciclo,$ubicacion,$pendientes=null)
    {
	if ($ciclo == date('Y'))    
                $ciclo_actual = " AND asignaciones.estado = 1";
        else
                $ciclo_actual = ""; 

	if (isset($pendientes))
		$where = " AND confirmado = 'N'";
	else
		$where = "";
	$sql = "-- obtener los docentes de una actividad en donde soy responsable
        SELECT DISTINCT 
                        'mi actividad evalua las materias del director' as motivo,
                        asignaciones.asignacion,
                        personas.persona as persona_evaluado,
                        personas.apellido || ', ' || nombres as evaluado_nombre_completo,
                        actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
                        ubicaciones.codigo as ubicacion_desc,
                        categorias.descripcion as rol_desc,
                        dimensiones.codigo as dimension_desc,
			departamentos.descripcion as departamento_desc,
                        asignaciones.eval_calificacion,
                        asignaciones.eval_confirmado,
			asignaciones.eval_notificacion

        FROM asignaciones LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
		LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
		LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension) 
		LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria) 
		LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
		LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)

        WHERE 	asignaciones.persona in (SELECT persona FROM asignaciones as asig WHERE autoeval_estado = 1 AND ciclo_lectivo = $ciclo
					AND actividad in (415,405,383,447,322,362,321,372,676,387,314,315,317,316))
		AND asignaciones.responsable = 'S'
                --AND actividades.se_evalua = 'S'
		AND asignaciones.dimension = 1
		AND asignaciones.autoeval_estado = 1
		AND asignaciones.ciclo_lectivo = $ciclo
		AND asignaciones.ubicacion = $ubicacion
                        $where
			$ciclo_actual
		";
	return toba::db()->consultar($sql);
    }    
    
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------    
    
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
	SELECT DISTINCT 
            'evaluo al resp por ambito' as motivo,
            asig2.asignacion,
            personas.persona as persona_evaluado,
            personas.apellido || ', ' || nombres as evaluado_nombre_completo,
            act2.descripcion as actividad_desc,
            act2.nombre as actividad_nombre,
            ubicaciones.codigo as ubicacion_desc,
            categorias.descripcion as rol_desc,
            dimensiones.codigo as dimension_desc,
	    departamentos.descripcion as departamento_desc,
            asig2.eval_calificacion,
            asig2.eval_confirmado,
            asig2.eval_notificacion
        FROM asignaciones, 
            actividades LEFT OUTER JOIN ambitos_a_evaluar ON (actividades.actividad = ambitos_a_evaluar.actividad_evaluador) 
            LEFT OUTER JOIN actividades as act2 ON (ambitos_a_evaluar.ambito_evaluado = act2.ambito), 
            asignaciones as asig2 
            LEFT OUTER JOIN dimensiones ON (asig2.dimension = dimensiones.dimension) 
            LEFT OUTER JOIN categorias ON (asig2.rol = categorias.categoria) 
            LEFT OUTER JOIN departamentos ON (asig2.departamento = departamentos.departamento) , 
            personas, 
            ubicaciones 
        WHERE     asignaciones.actividad = actividades.actividad
            AND asig2.eval_estado = 1 
            AND asignaciones.persona = $persona
            AND asignaciones.ciclo_lectivo = $ciclo
            AND asignaciones.responsable = 'S'
            AND actividades.se_evalua = 'S'
            AND asignaciones.ubicacion = ambitos_a_evaluar.ubicacion_evaluador
            AND asig2.ubicacion = ambitos_a_evaluar.ubicacion_evaluado
            AND act2.actividad = asig2.actividad
            AND asig2.persona = personas.persona
            AND asig2.ubicacion = ubicaciones.ubicacion
            AND asig2.responsable = ambitos_a_evaluar.responsable
            AND asig2.ciclo_lectivo = $ciclo
            AND asignaciones.persona <> asig2.persona
        --    AND ( (asig2.fecha_desde < '$ciclo-11-01' AND asig2.dimension = 4) or (asig2.fecha_desde < '$ciclo-11-01' AND asig2.dimension <> 4) )
        --    AND asignaciones.fecha_desde < '$ciclo-11-01'
	    $where
            $ciclo_actual
            ";
	return toba::db()->consultar($sql);
    }
    
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------
    
    function evaluador_es_academica($persona,$ciclo)
    {
        $sql = "SELECT asignaciones.ubicacion 
                FROM asignaciones 
                WHERE asignaciones.persona = $persona
                    AND asignaciones.ciclo_lectivo = $ciclo
                    AND asignaciones.autoeval_estado = 1 
                    AND asignaciones.actividad in (427,688,687,443) -- secretario academico, colaborador academico
                    AND asignaciones.fecha_desde < '$ciclo-12-01'
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
                estados.descripcion as estado_desc,
                departamentos.descripcion as departamento_desc,
                actividades.nombre as actividad_nombre,
		actividades.descripcion as actividad_desc
		FROM asignaciones LEFT OUTER JOIN personas as pp ON (asignaciones.eval_evaluador = pp.persona)
                        LEFT OUTER JOIN estados ON (asignaciones.eval_estado = estados.estado)
                        LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)                       
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
                        LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
                        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
                        LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento) 
                        
		WHERE 	
			asignaciones.eval_confirmado = 'N' 
                        AND asignaciones.eval_estado = 1 AND $where 
                            AND actividades.se_evalua = 'S'
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
