<?php

class co_autoevaluaciones
{

//
//
// ------------------------------------------ AUTEVALUACIONES POR DOCENTE (FICHA DOCENTE)-----------------------------------------
//
//
    
     function get_autoevaluaciones($where)
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
                        LEFT OUTER JOIN estados ON (asignaciones.autoeval_estado = estados.estado)
                        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
                WHERE   $where
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }
   
    
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
	$sql = "SELECT DISTINCT ON (nombre_completo) *
		FROM (	SELECT apellido || ', ' || nombres as nombre_completo,
			personas.persona,
			'' as ficha_docente_path,
			'' as confirmado,
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
                    autoevaluaciones.confirmado,
                    autoevaluaciones.ciclo_lectivo,
                    designaciones.ubicacion,
                    designaciones.departamento
                    FROM personas, 
                        autoevaluaciones LEFT OUTER JOIN designaciones ON (autoevaluaciones.persona = designaciones.persona AND designaciones.estado in (1,4))

                    WHERE personas.persona = autoevaluaciones.persona 
                    AND autoevaluaciones.confirmado = 'N'
		) as sub
		WHERE $where AND persona in (SELECT persona FROM designaciones WHERE extract (year from fecha_desde) <= $ciclo)
		ORDER BY nombre_completo

		";
        return toba::db()->consultar($sql);
    }
    
    function get_autoevaluaciones_control_personas($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT autoevaluaciones_control_personas.autoevaluacion_control_persona,
            director.apellido || ', ' || director.nombres as director_nombre,
                        personas.apellido || ', ' || personas.nombres as persona_nombre
            FROM autoevaluaciones_control_personas LEFT OUTER JOIN personas as director 
            ON (autoevaluaciones_control_personas.director = director.persona)
            LEFT OUTER JOIN personas
            ON (autoevaluaciones_control_personas.persona = personas.persona)
            ORDER BY director.apellido, personas.apellido
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_autoevaluaciones_a_controlar($persona, $ciclo)
    {
        $sql = "SELECT autoevaluaciones_control_personas.autoevaluacion_control_persona,
                        director.apellido || ', ' || director.nombres as director_nombre,
                        personas.apellido || ', ' || personas.nombres as persona_nombre,
                        (SELECT departamentos.descripcion FROM designaciones 
                        LEFT OUTER JOIN departamentos ON designaciones.departamento = departamentos.departamento 
                        WHERE designaciones.estado in (1,4,5) AND designaciones.persona = autoevaluaciones_control_personas.persona ORDER BY designaciones.departamento LIMIT 1) as depto,
                    
                        (SELECT ubicaciones.descripcion FROM designaciones 
                        LEFT OUTER JOIN ubicaciones ON designaciones.ubicacion = ubicaciones.ubicacion 
                        WHERE designaciones.estado in (1,4,5) AND designaciones.persona = autoevaluaciones_control_personas.persona ORDER BY ubicaciones.ubicacion DESC LIMIT 1) as ubicacion_desc,
 
                        CASE WHEN autoevaluaciones.ciclo_lectivo = $ciclo THEN 'S' ELSE 'N' END as subio_ficha,
                        CASE WHEN autoevaluaciones.ciclo_lectivo = $ciclo AND autoevaluaciones.confirmado = 'S' THEN 'S' ELSE 'N' END as confirmo_ficha,
                        CASE WHEN autoevaluaciones.ciclo_lectivo = $ciclo THEN autoevaluaciones.ficha_docente_path END as path_ficha
                        
            FROM autoevaluaciones_control_personas LEFT OUTER JOIN personas as director ON (autoevaluaciones_control_personas.director = director.persona)
            LEFT OUTER JOIN personas ON (autoevaluaciones_control_personas.persona = personas.persona)
            LEFT OUTER JOIN autoevaluaciones ON (personas.persona = autoevaluaciones.persona AND autoevaluaciones.ciclo_lectivo = $ciclo)
            WHERE director = $persona
            ORDER BY depto, ubicacion_desc, director.apellido, personas.apellido";
        return toba::db()->consultar($sql);
    }
    
    function get_actividades_a_controlar($persona, $ciclo)
    {
        $sql = "SELECT DISTINCT
                    personas.apellido || ', ' || personas.nombres as persona_nombre,
                    actividades.descripcion as actividad_desc,
                    ubicaciones.codigo as ubicacion_desc,	
                    asignaciones.responsable,
                    CASE 
                        WHEN asignaciones.autoeval_confirmado is null THEN 'NO'
                        WHEN asignaciones.autoeval_confirmado = 'N' THEN 'NO'
                        ELSE 'SI'
                    END as autoevaluo,
                    CASE 
                        WHEN asignaciones.eval_confirmado is null THEN 'NO'
                        WHEN asignaciones.eval_confirmado = 'N' THEN 'NO'
                        ELSE 'SI' 
                    END as evaluado,
                    asignaciones.eval_notificacion as notificado
                FROM
                    asignaciones LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
                    LEFT OUTER JOIN ambitos_a_evaluar ON (actividades.ambito = ambitos_a_evaluar.ambito_evaluado)
                    LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
                    LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
                WHERE
                    asignaciones.ciclo_lectivo = $ciclo
                    AND ambitos_a_evaluar.actividad_evaluador in (SELECT actividad FROM asignaciones as as2 
                                                                WHERE persona = $persona AND as2.ciclo_lectivo = $ciclo        
                                                                AND as2.estado = 1)
                    AND asignaciones.autoeval_estado = 1
                    AND asignaciones.eval_estado = 1
                    --AND asignaciones.estado = 15
                    AND actividades.se_evalua = 'S'
                ORDER BY persona_nombre
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
        $sql = "SELECT asignaciones.*
                FROM asignaciones
                WHERE asignaciones.asignacion = $asignacion
                ";
        return toba::db()->consultar_fila($sql);
    }  

    function get_actividades_sin_confirmar($persona,$ciclo)
    {
	$sql = "SELECT autoeval_confirmado
		FROM asignaciones
		WHERE persona = $persona AND autoeval_estado = 1 AND autoeval_confirmado = 'N' AND ciclo_lectivo = $ciclo
		";
	return toba::db()->consultar($sql);
    }

    function get_autoevaluaciones_por_act_persona($persona,$ciclo)
    {
	$sql = "SELECT  asignaciones.*,
                        personas.apellido || ', ' || nombres as nombre_completo,
			asignaciones.actividad,
			asignaciones.carrera_academica,
			ubicaciones.codigo as ubicacion_desc,
			dimensiones.codigo as dimension_desc,
			actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
			categorias.descripcion as rol_desc,
			asignaciones.responsable
                FROM asignaciones LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension),
                        personas
			
		WHERE 	asignaciones.persona = $persona 
                        AND asignaciones.persona = personas.persona
			AND asignaciones.autoeval_estado = 1
                        AND actividades.se_evalua = 'S'
			AND asignaciones.ciclo_lectivo = $ciclo
                        AND asignaciones.actividad <> 347 -- A DEFINIR
                ORDER BY actividad_desc
		";
	return toba::db()->consultar($sql);
    }

    function get_act_pendientes($where)
    {
	$sql = "
		SELECT 	DISTINCT
                        asignaciones.asignacion,
			apellido || ', ' || nombres as nombre_completo, 
			autoeval_confirmado, 
			caracteres.descripcion as caracter,
			actividades.descripcion as actividad_desc,
                        actividades.nombre as actividad_nombre,
			asignaciones.ciclo_lectivo, 
			asignaciones.ubicacion, 
                        (SELECT codigo FROM ubicaciones WHERE ubicacion = asignaciones.ubicacion) as ubicacion_desc,
                        estados.descripcion as estado_desc,
			designaciones.departamento 
		FROM     personas LEFT OUTER JOIN asignaciones ON (personas.persona = asignaciones.persona) 
		            LEFT OUTER JOIN designaciones ON (asignaciones.designacion = designaciones.designacion)
		            LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN caracteres ON (designaciones.caracter = caracteres.caracter)
                        LEFT OUTER JOIN estados ON (asignaciones.autoeval_estado = estados.estado)
			LEFT OUTER JOIN personas_perfiles ON (personas.persona = personas_perfiles.persona)
		WHERE
	                (asignaciones.autoeval_confirmado = 'N' or asignaciones.autoeval_confirmado is null or asignaciones.autoeval_confirmado = '') -- pendiente, no fue confirmada por el docente
	   
       		        AND designaciones.designacion_tipo = 1 -- designacion tipo alta                    
        		AND actividades.se_evalua = 'S'
                        AND asignaciones.autoeval_estado = 1
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
	$sql = "SELECT asignaciones.*,
			categorias.descripcion as rol_desc,
			(SELECT substring(actividades.descripcion from 1 for 100)) as actividad_desc,
                        actividades.nombre as actividad_nombre,
			ubicaciones.descripcion as ubicacion_desc,
			personas.apellido || ', ' || personas.nombres as nombre_completo
		FROM asignaciones 
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
			LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad) 
			LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
			LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
		WHERE asignaciones.asignacion = $asignacion
		";

	return toba::db()->consultar_fila($sql);
    }
}

?>
