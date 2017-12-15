<?php

class co_autoevaluaciones
{

//
//
// ------------------------------------------ AUTEVALUACIONES POR DOCENTE (FICHA DOCENTE)-----------------------------------------
//
//

    function get_ficha_de_docente($persona,$ciclo)
    {
        $sql = "SELECT 	autoevaluaciones.*,
			personas.apellido || ', ' || nombres as nombre_completo
		FROM 	autoevaluaciones, personas
		WHERE   autoevaluaciones.persona = $persona
			AND ciclo_lectivo = $ciclo
			AND autoevaluaciones.persona = personas.persona
		ORDER BY ficha_docente_fecha DESC LIMIT 1
        ";
	return toba::db()->consultar_fila($sql);
    }

    function get_fichas_de_docente($persona)
    {
        $sql = "SELECT 	autoevaluaciones.*,
			personas.apellido || ', ' || nombres as nombre_completo
		FROM 	autoevaluaciones, personas
		WHERE   autoevaluaciones.persona = $persona
			AND autoevaluaciones.persona = personas.persona
		ORDER BY ficha_docente_fecha 
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_ficha_de_docente_por_asignacion($asignacion)
    {
        $sql = "SELECT 	autoevaluaciones.*
		FROM 	autoevaluaciones, asignaciones
		WHERE   autoevaluaciones.persona = asignaciones.persona
			AND asignaciones.asignacion = $asignacion
		ORDER BY ficha_docente_fecha DESC LIMIT 1
        ";
	return toba::db()->consultar_fila($sql);
    }

    function get_ficha_pendientes($where,$ciclo)
    {
	$sql = "SELECT DISTINCT *
		FROM (	SELECT apellido || ', ' || nombres as nombre_completo,
			personas.persona,
			'' as ficha_docente_path,
			'' as confirmado,
			designaciones.carrera_academica,
			$ciclo as ciclo_lectivo,
			designaciones.ubicacion,
			designaciones.departamento
			FROM 	personas, 
				designaciones
			WHERE personas.persona not in (SELECT persona FROM autoevaluaciones WHERE ciclo_lectivo = $ciclo)
				AND personas.persona = designaciones.persona
				AND designaciones.designacion_tipo = 1 
				AND designaciones.estado in (1,4)
		UNION
			SELECT apellido || ', ' || nombres as nombre_completo,
			personas.persona,
			autoevaluaciones.ficha_docente_path,
			confirmado,
			designaciones.carrera_academica,
			autoevaluaciones.ciclo_lectivo,
			designaciones.ubicacion,
			designaciones.departamento
			FROM personas, 
				autoevaluaciones, 
				designaciones
			WHERE personas.persona = autoevaluaciones.persona 
			AND autoevaluaciones.confirmado = 'N'
			AND personas.persona = designaciones.persona
			AND designaciones.designacion_tipo = 1
			AND designaciones.estado in (1,4)
			) as sub
		WHERE $where AND persona in (SELECT persona FROM designaciones WHERE extract (year from fecha_desde) <= $ciclo)
		ORDER BY nombre_completo

		";
        return toba::db()->consultar($sql);
    }

//
//
// ------------------------------------------ AUTEVALUACIONES POR ACTIVIDAD ------------------------------------------------------
//
//

    function get_informes_docente($asignacion)
    {   
        $sql = "SELECT autoevaluaciones_por_act.*
                FROM autoevaluaciones_por_act
                WHERE autoevaluaciones_por_act.asignacion = $asignacion
                ";
        return toba::db()->consultar($sql);
    }  

    function get_actividades_sin_confirmar($persona)
    {
	$sql = "SELECT confirmado
		FROM autoevaluaciones_por_act
		WHERE persona = $persona AND estado = 1 AND confirmado = 'N'
		";
	return toba::db()->consultar($sql);
    }

    function get_autoevaluaciones_por_act_persona($persona,$ciclo)
    {
	$sql = "SELECT  apa.*,
                        personas.apellido || ', ' || nombres as nombre_completo,
                        CASE WHEN apa.pregunta5 = '' THEN resultado_resp
                        ELSE pregunta5 END as calificacion,
			asignaciones.actividad,
			asignaciones.carrera_academica,
			ubicaciones.codigo as ubicacion_desc,
			dimensiones.codigo as dimension_desc,
			actividades.descripcion as actividad_desc,
			categorias.descripcion as rol_desc,
			asignaciones.responsable
                FROM autoevaluaciones_por_act as apa LEFT OUTER JOIN ubicaciones ON (apa.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN asignaciones ON (apa.asignacion = asignaciones.asignacion)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension),
                        personas
			
		WHERE 	apa.persona = $persona 
                        AND apa.persona = personas.persona
			AND apa.estado = 1
			AND apa.ciclo_lectivo = $ciclo
		";
	return toba::db()->consultar($sql);
    }

    function get_act_pendientes($where)
    {
	$sql = "
		SELECT 	DISTINCT
			apellido || ', ' || nombres as nombre_completo, 
			confirmado, 
			caracteres.descripcion as caracter,
			actividades.descripcion as actividad_desc,
			autoevaluaciones_por_act.ciclo_lectivo, 
			designaciones.ubicacion, 
                        estados.descripcion as estado_desc,
			designaciones.departamento 
		FROM     personas LEFT OUTER JOIN autoevaluaciones_por_act ON (personas.persona = autoevaluaciones_por_act.persona) 
		            LEFT OUTER JOIN asignaciones ON (autoevaluaciones_por_act.asignacion = asignaciones.asignacion)
		            LEFT OUTER JOIN designaciones ON (asignaciones.designacion = designaciones.designacion)
		            LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN caracteres ON (designaciones.caracter = caracteres.caracter)
                        LEFT OUTER JOIN estados ON (autoevaluaciones_por_act.estado = estados.estado)
			LEFT OUTER JOIN personas_perfiles ON (personas.persona = personas_perfiles.persona)
		WHERE
	                autoevaluaciones_por_act.confirmado = 'N' -- pendiente, no fue confirmada por el docente
	    --            AND autoevaluaciones_por_act.estado = 1 -- autoeval activa
       		        AND designaciones.designacion_tipo = 1 -- designacion tipo alta                    
        		AND actividades.se_evalua = 'S'
	    --        AND designaciones.caracter = 2 -- solo los regulares, interinos no evaluan
	    --        AND designaciones.categoria <> 6 -- no muestro los ayudantes de segunda
	            AND perfil = 1 -- docente	
	            AND personas.estado_docente = 1 -- persona con estado activo
			AND $where
			ORDER BY nombre_completo
		";
	return toba::db()->consultar($sql);
    }

    function get_tipo_informe_por_dim($dimension,$ambito=null)
    {
	if (!isset($dimension)) $where_dimension = '1=1'; else $where_dimension = "dimension = $dimension";
	if (!isset($ambito)) $where_ambito = '1=1'; else $where_ambito = "ambito = $ambito";
	
	$sql = "SELECT 
			tipos_informes.tipo_informe as tipo,
		      	tipos_informes.descripcion
		FROM tipos_informes, tipos_informes_dimensiones
		WHERE tipos_informes.tipo_informe = tipos_informes_dimensiones.tipo_informe
			AND $where_dimension AND $where_ambito
		";
	return toba::db()->consultar($sql);
    }

    function get_autoevaluacion_por_asignacion($asignacion)
    {
	$sql = "SELECT apa.*,
			categorias.descripcion as rol_desc,
			(SELECT substring(actividades.descripcion from 1 for 100)) as actividad_desc,
			ubicaciones.descripcion as ubicacion_desc,
			personas.apellido || ', ' || personas.nombres as nombre_completo
		FROM autoevaluaciones_por_act as apa LEFT OUTER JOIN asignaciones ON (apa.asignacion = asignaciones.asignacion)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad) 
			LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
			LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
		WHERE apa.asignacion = $asignacion
		";

	return toba::db()->consultar_fila($sql);
    }
}

?>
