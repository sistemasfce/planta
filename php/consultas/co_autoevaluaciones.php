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

    function get_cantidad_fichas($ciclo,$cuenta=1)
    {
        if ($cuenta == 1) {
            $select = 'SELECT COUNT (*) ';
            $order = '';
        } 
        else {
            $select = "SELECT nombre_completo as persona_nombre";
            $order = ' ORDER BY persona_nombre';
        } 
       $sql =   "$select FROM (SELECT DISTINCT apellido || ', ' || nombres as nombre_completo
			FROM 	personas, 
				designaciones
			WHERE  personas.persona = designaciones.persona
				AND designaciones.designacion_tipo = 1 
				AND designaciones.estado in (1,4,5)) as c1
                                $order
                ";
        if ($cuenta == 1) 
            return toba::db()->consultar_fila($sql);
        else
            return toba::db()->consultar($sql);
    }
    
    function get_ficha_pendientes($where,$ciclo)
    {
	$sql = "SELECT DISTINCT ON (nombre_completo) *
		FROM (	SELECT apellido || ', ' || nombres as nombre_completo,
                        apellido || ', ' || nombres as persona_nombre,
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
				AND designaciones.estado in (1,4,5)
		UNION
                    SELECT apellido || ', ' || nombres as nombre_completo,
                    apellido || ', ' || nombres as persona_nombre,
                    personas.persona,
                    autoevaluaciones.ficha_docente_path,
                    autoevaluaciones.confirmado,
                    autoevaluaciones.ciclo_lectivo,
                    designaciones.ubicacion,
                    designaciones.departamento
                    FROM personas, 
                        autoevaluaciones LEFT OUTER JOIN designaciones ON (autoevaluaciones.persona = designaciones.persona)
                    WHERE personas.persona = autoevaluaciones.persona 
                        AND autoevaluaciones.confirmado = 'N'
                        AND designaciones.estado in (1,4,5)
                        AND autoevaluaciones.ciclo_lectivo = $ciclo
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
                        WHEN asignaciones.autoeval_confirmado is null THEN 'N'
                        WHEN asignaciones.autoeval_confirmado = 'N' THEN 'N'
                        ELSE 'S'
                    END as autoevaluo,
                    CASE 
                        WHEN asignaciones.eval_confirmado is null THEN 'N'
                        WHEN asignaciones.eval_confirmado = 'N' THEN 'N'
                        ELSE 'S' 
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
                    
            UNION

            SELECT DISTINCT
                    personas.apellido || ', ' || personas.nombres as persona_nombre,
                    actividades.descripcion as actividad_desc,
                    ubicaciones.codigo as ubicacion_desc,	
                    asignaciones.responsable,
                    CASE 
                        WHEN asignaciones.autoeval_confirmado is null THEN 'N'
                        WHEN asignaciones.autoeval_confirmado = 'N' THEN 'N'
                        ELSE 'S'
                    END as autoevaluo,
                    CASE 
                        WHEN asignaciones.eval_confirmado is null THEN 'N'
                        WHEN asignaciones.eval_confirmado = 'N' THEN 'N'
                        ELSE 'S' 
                    END as evaluado,
                    asignaciones.eval_notificacion as notificado
               FROM
                    asignaciones LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
                    LEFT OUTER JOIN actividades_a_seguir ON (actividades.actividad = actividades_a_seguir.actividad_seguida)
                    LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
                    LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion),
                    asignaciones as asig2
                WHERE
                    asignaciones.ciclo_lectivo = $ciclo

		AND asig2.persona = $persona
		AND asig2.ciclo_lectivo = $ciclo
		AND asig2.estado = 1
		AND asig2.actividad = actividades_a_seguir.actividad_sigue
		AND asig2.ubicacion = actividades_a_seguir.ubicacion_sigue
		AND actividades_a_seguir.ubicacion_sigue = asignaciones.ubicacion
                    
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
    function es_seguimiento($persona,$ciclo)
    {
        $sql = "SELECT actividad_evaluador
                FROM ambitos_a_evaluar
                WHERE ambitos_a_evaluar.actividad_evaluador in (SELECT actividad FROM asignaciones as as2 
                                                                WHERE persona = $persona AND as2.ciclo_lectivo = $ciclo        
                                                                AND as2.estado = 1)
                                                                
                UNION
                    SELECT actividades_a_seguir.actividad_sigue
                    FROM actividades_a_seguir, asignaciones as asig2
                    WHERE asig2.persona = $persona
                        AND asig2.ciclo_lectivo = $ciclo
                        AND asig2.estado = 1
                        AND asig2.actividad = actividades_a_seguir.actividad_sigue
                        AND asig2.ubicacion = actividades_a_seguir.ubicacion_sigue
                
                ";
        return toba::db()->consultar($sql);
    }

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
    
    // devuelve la cantidad de personas de una dimension para autoevaluarse, si el parametro personas es 1
    // pone un distinct para obtener la cantidad de personas, sino devuelve la cantidad de actividades por personas repetidas
    function get_personas_por_dimension($ciclo,$dimension,$personas,$cuenta=1) 
    {
        if ($dimension == 0)
                $where = 'dimension not in (1,2,3,4,5)';
        else 
            $where = ' dimension = '.$dimension;
        if ($personas == 1)
            $distintos = 'DISTINCT';
        else
            $distintos = '';
        if ($cuenta == 1) {
            $select = 'SELECT COUNT('.$distintos.' asignaciones.persona)';
            $order = '';
        } 
        else {
            $select = "SELECT $distintos personas.apellido || ', ' || personas.nombres as persona_nombre, actividades.descripcion as actividad_desc";
            $order = ' ORDER BY persona_nombre, actividad_desc';
        }  
        $sql = "-- get_personas_por_dimension
		$select
                FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND autoeval_estado = 1 AND eval_estado = 1 AND $where 
                AND actividades.se_evalua = 'S'
                $order
        ";
         if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
             return toba::db()->consultar($sql);
    }
    
    // devuelve la cantidad de personas de una dimension que no tiene la autoeval 
    function get_pendientes_por_dimension($ciclo,$dimension,$tipo,$personas,$cuenta=1) 
    {
        if ($tipo == 'autoeval') {
            $where = 'asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 '
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND autoeval_estado = 1 AND (autoeval_calificacion is not null or autoeval_calificacion <> ''))";
        } else {
             $where = 'asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 '
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND eval_estado = 1 AND (eval_calificacion is not null or eval_calificacion <> ''))";           
        }
        if ($personas == 1)
            $distintos = 'DISTINCT';
        else
            $distintos = '';
        if ($cuenta == 1) {
            $select = 'SELECT COUNT('.$distintos.' asignaciones.persona)';
            $order = '';
        } 
        else {
            $select = "SELECT $distintos personas.apellido || ', ' || personas.nombres as persona_nombre, actividades.descripcion as actividad_desc";
            $order = ' ORDER BY persona_nombre, actividad_desc';
        }        
        $sql = "-- get_pendientes_por_dimension
		$select
                FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                    AND actividades.se_evalua = 'S'
                    AND $where
                $order        
        ";
        if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
             return toba::db()->consultar($sql);
    }    
    
    // devuelve la cantidad de personas de una dimension que no tiene la autoeval confirmada
    function get_no_conf_por_dimension($ciclo,$dimension,$tipo,$personas,$cuenta=1) 
    {
        if ($tipo == 'autoeval') {
            $where = 'asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 '
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND autoeval_estado = 1 AND autoeval_confirmado = 'S')";
        } else {
             $where = 'asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 '
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND eval_estado = 1 AND eval_confirmado = 'S')";          
        }
        if ($personas == 1)
            $distintos = 'DISTINCT';
        else
            $distintos = '';
        if ($cuenta == 1) {
            $select = 'SELECT COUNT('.$distintos.' asignaciones.persona)';
            $order = '';
        } 
        else {
            $select = "SELECT $distintos personas.apellido || ', ' || personas.nombres as persona_nombre, actividades.descripcion as actividad_desc";
            $order = ' ORDER BY persona_nombre, actividad_desc';
        }  
        $sql = "-- get_no_conf_por_dimension
		$select
                FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                        AND actividades.se_evalua = 'S'
                    	AND $where
                $order            
        ";
        if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
             return toba::db()->consultar($sql);
    }

    function get_notifico_por_dimension($ciclo,$dimension,$personas,$cuenta=1) 
    {
        if ($personas == 1)
            $distintos = 'DISTINCT';
        else
            $distintos = '';
        if ($cuenta == 1) {
            $select = 'SELECT COUNT('.$distintos.' asignaciones.persona)';
            $order = '';
        } 
        else {
            $select = "SELECT $distintos personas.apellido || ', ' || personas.nombres as persona_nombre, actividades.descripcion as actividad_desc";
            $order = ' ORDER BY persona_nombre, actividad_desc';
        }          
        $sql = "-- get_notifico_por_dimension
            $select
            FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
            LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                AND actividades.se_evalua = 'S'
                AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension 
                        AND eval_notificacion = 'S')
            $order
        ";
        if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
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
    
    function get_matriz_por_sede($ciclo,$dimension,$personas)
    {
        if ($personas == 1)
            $distintos = 'DISTINCT';
        else
            $distintos = '';
        $sql = "SELECT 	(SELECT codigo FROM ubicaciones WHERE ubicacion = c1.ubicacion) as sede, 
	(SELECT descripcion FROM departamentos WHERE departamento = c1.departamento) as depto, 
	c1.count as auto_personas, 
	c2.count as auto_pen,
	c3.count as auto_pen_conf,
        c1.count as eval_personas,
	c4.count as eval_pen,
	c5.count as eval_pen_conf,
        c1.count as noti_personas,
	c6.count as noti_notificados

        FROM (

        SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
        FROM asignaciones 
                        LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                        LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND autoeval_estado = 1 AND eval_estado = 1 AND  dimension = $dimension 
                        AND actividades.se_evalua = 'S'
        GROUP BY asignaciones.ubicacion, asignaciones.departamento) as c1

        LEFT JOIN 

        (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
        FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                        LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                            AND actividades.se_evalua = 'S'
                            AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND autoeval_estado = 1 
                            AND (autoeval_calificacion is not null or autoeval_calificacion <> ''))
        GROUP BY asignaciones.ubicacion, asignaciones.departamento) c2 ON c1.ubicacion = c2.ubicacion AND c1.departamento = c2.departamento

        LEFT JOIN

        (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
                        FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                        LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                                AND actividades.se_evalua = 'S'
                                AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                                AND autoeval_estado = 1 AND autoeval_confirmado = 'S')
        GROUP BY asignaciones.ubicacion, asignaciones.departamento) c3 ON c1.ubicacion = c3.ubicacion AND c1.departamento = c3.departamento

        LEFT JOIN

        (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
        FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                        LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                            AND actividades.se_evalua = 'S'
                            AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND eval_estado = 1 
                            AND (eval_calificacion is not null or eval_calificacion <> ''))		
        GROUP BY asignaciones.ubicacion, asignaciones.departamento) c4 ON c1.ubicacion = c4.ubicacion AND c1.departamento = c4.departamento

        LEFT JOIN

        (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
        FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                        LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                                AND actividades.se_evalua = 'S'
                                AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND eval_estado = 1 AND eval_confirmado = 'S')
        GROUP BY asignaciones.ubicacion, asignaciones.departamento) c5 ON c1.ubicacion = c5.ubicacion AND c1.departamento = c5.departamento

        LEFT JOIN

        (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
        FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                    LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                        AND actividades.se_evalua = 'S'
                        AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension 
                                AND eval_notificacion = 'S')
        GROUP BY asignaciones.ubicacion, asignaciones.departamento) c6 ON c1.ubicacion = c6.ubicacion AND c1.departamento = c6.departamento
        ";
        return toba::db()->consultar($sql);
    }
}

?>
