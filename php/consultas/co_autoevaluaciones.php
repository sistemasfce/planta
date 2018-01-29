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
        $sql = "SELECT asignaciones.*
                FROM asignaciones
                WHERE asignaciones.asignacion = $asignacion
                ";
        return toba::db()->consultar($sql);
    }  

    function get_actividades_sin_confirmar($persona)
    {
	$sql = "SELECT autoeval_confirmado
		FROM asignaciones
		WHERE persona = $persona AND autoeval_estado = 1 AND autoeval_confirmado = 'N'
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
			AND asignaciones.ciclo_lectivo = $ciclo
		";
	return toba::db()->consultar($sql);
    }

    function get_act_pendientes($where)
    {
	$sql = "
		SELECT 	DISTINCT
			apellido || ', ' || nombres as nombre_completo, 
			autoeval_confirmado, 
			caracteres.descripcion as caracter,
			actividades.descripcion as actividad_desc,
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
	                asignaciones.autoeval_confirmado = 'N' -- pendiente, no fue confirmada por el docente
	   
       		        AND designaciones.designacion_tipo = 1 -- designacion tipo alta                    
        		AND actividades.se_evalua = 'S'
	
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
