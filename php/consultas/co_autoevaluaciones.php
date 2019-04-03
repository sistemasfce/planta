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

    function get_cantidad_fichas($ciclo,$cuenta=1,$departamento,$ubicacion,$dimension=-1)
    {
        if ($dimension == -1) {
            $dim_where = '';
        } else {
            $dim_where = " AND designaciones.dimension = $dimension ";
        }
        $where = '';
        if ($cuenta == 1) {
            $select = 'SELECT COUNT (*) ';
            $order = '';
        } 
        else {
            $select = "SELECT nombre_completo as persona_nombre, '' as actividad_desc";
            $order = ' ORDER BY persona_nombre';
        } 
        if($departamento and $ubicacion){
            $where = "AND designaciones.ubicacion = $ubicacion AND designaciones.departamento = $departamento";
        }
       $sql =   "$select FROM (SELECT DISTINCT apellido || ', ' || nombres as nombre_completo  
			FROM 	personas, 
				designaciones
			WHERE  personas.persona = designaciones.persona
				AND designaciones.designacion_tipo = 1 
				AND designaciones.estado in (1,4,5) 
                                $dim_where
                                AND designaciones.categoria <> 6 $where) as c1
                                $order
                ";
        if ($cuenta == 1) 
            return toba::db()->consultar_fila($sql);
        else
            return toba::db()->consultar($sql);
    }
    
    function get_cantidad_fichas_por_dim($dimension)
    {
        $sql = "SELECT (SELECT codigo FROM ubicaciones WHERE ubicacion = designaciones.ubicacion) as sede, 
                (SELECT descripcion FROM departamentos WHERE departamento = designaciones.departamento) as depto, 
                COUNT(designaciones.persona)

			FROM 	personas, 
				designaciones
			WHERE  personas.persona = designaciones.persona
				AND designaciones.designacion_tipo = 1 
				AND designaciones.estado in (1,4,5)
				AND designaciones.dimension = $dimension
                GROUP BY designaciones.ubicacion, designaciones.departamento
                ORDER BY ubicacion, departamento
        ";
        return toba::db()->consultar($sql);
    }
    
    function get_ficha_pendientes($where,$ciclo,$departamento,$ubicacion,$dimension=-1)
    {
        if ($dimension == -1) {
            $dim_where = '';
        } else {
            $dim_where = " AND designaciones.dimension = $dimension ";
        }
        $where2 = '';
        if($departamento and $ubicacion){
            $where2 = "AND designaciones.ubicacion = $ubicacion AND designaciones.departamento = $departamento";
        }
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
                                AND designaciones.categoria <> 6
                                $dim_where
                                $where2
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
                        AND designaciones.categoria <> 6
                        $dim_where
                        AND autoevaluaciones.ciclo_lectivo = $ciclo
                        $where2
		) as sub
		WHERE $where AND persona in (SELECT persona FROM designaciones WHERE extract (year from fecha_desde) <= $ciclo)
		ORDER BY nombre_completo

		";
        return toba::db()->consultar($sql);
    }
    
    function get_fichas_por_fecha($where)
    {
        $fecha_confirma = str_replace('fecha','confirmado_fecha',$where);
        $fecha_sube = str_replace('fecha','ficha_docente_fecha',$where);
        
        $sql = "SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Confirmó ficha' as accion,
                        confirmado_fecha as fecha
                FROM autoevaluaciones LEFT OUTER JOIN personas ON autoevaluaciones.persona = personas.persona
                WHERE $fecha_confirma
                
                UNION

                SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Subió ficha' as accion,
                        ficha_docente_fecha as fecha
                FROM autoevaluaciones LEFT OUTER JOIN personas ON autoevaluaciones.persona = personas.persona
                WHERE $fecha_sube

             ORDER BY nombre_completo";
        return toba::db()->consultar($sql);
    }

    function get_autoevaluaciones_por_fecha($where)
    {
        $fecha_confirma = str_replace('fecha','autoeval_confirmado_fecha',$where);
        $fecha_sube = str_replace('fecha','autoeval_calificacion_fecha',$where);
        
        $sql = "SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Confirmó autoevaluación' as accion,
                        actividades.descripcion as actividad_desc,
                        autoeval_confirmado_fecha as fecha,
                        ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc
                FROM asignaciones LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                            LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN ubicaciones ON asignaciones.ubicacion = ubicaciones.ubicacion
                            LEFT OUTER JOIN dimensiones ON asignaciones.dimension = dimensiones.dimension
                WHERE $fecha_confirma
                
                UNION

                SELECT personas.apellido || ', ' || personas.nombres as nombre_completo,
                        'Cargó autoevaluación' as accion,
                        actividades.descripcion as actividad_desc,
                        autoeval_calificacion_fecha as fecha,
                        ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc
                FROM asignaciones LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                            LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN ubicaciones ON asignaciones.ubicacion = ubicaciones.ubicacion
                            LEFT OUTER JOIN dimensiones ON asignaciones.dimension = dimensiones.dimension
                WHERE $fecha_sube

             ORDER BY nombre_completo";
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
    function get_personas_por_dimension($ciclo,$dimension,$personas,$cuenta=1,$departamento,$ubicacion) 
    {
        if($departamento and $ubicacion){
            $where2 = "AND asignaciones.ubicacion = $ubicacion AND asignaciones.departamento = $departamento";
        }
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
                WHERE ciclo_lectivo = $ciclo AND estado = 15 "
                . "AND autoeval_estado = 1 AND eval_estado = 1 AND $where $where2
                AND actividades.se_evalua = 'S'
                $order
        ";
         if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
             return toba::db()->consultar($sql);
    }
    
    // devuelve la cantidad de personas de una dimension que no tiene la autoeval 
    function get_pendientes_por_dimension($ciclo,$dimension,$tipo,$personas,$cuenta=1,$departamento,$ubicacion) 
    {
        $evaluacion = '';
        if ($personas == 1) {
            $distintos = 'DISTINCT';
            $pers_act = 'persona';
        }
        else {
            $distintos = '';
            $pers_act = 'asignacion';
        }
        if($departamento and $ubicacion){
            $where2 = "AND asignaciones.ubicacion = $ubicacion AND asignaciones.departamento = $departamento";
        }        
        if ($tipo == 'autoeval') {
            $where = "asignaciones.$pers_act in (SELECT $pers_act FROM asignaciones as asig2 LEFT OUTER JOIN actividades as ac2 ON asig2.actividad = ac2.actividad "
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension . ""
                . " AND autoeval_estado = 1 AND ac2.se_evalua = 'S' AND (autoeval_calificacion is null or autoeval_calificacion = ''))";
        } else {
             $where = "asignaciones.$pers_act in (SELECT $pers_act FROM asignaciones as asig2 LEFT OUTER JOIN actividades as ac2 ON asig2.actividad = ac2.actividad "
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND eval_estado = 1 AND ac2.se_evalua = 'S' AND (eval_calificacion is null or eval_calificacion = ''))";           
        }
        if ($cuenta == 1) {
            $select = 'SELECT COUNT('.$distintos.' asignaciones.persona)';
            $order = '';
        } 
        else {
            $select = "SELECT $distintos personas.apellido || ', ' || personas.nombres as persona_nombre, actividades.descripcion as actividad_desc";
           
            $order = ' ORDER BY persona_nombre, actividad_desc';
            if ($tipo == 'autoeval') {
                 $evaluacion = " AND (autoeval_calificacion is null or autoeval_calificacion = '')";
            } else {
                $evaluacion = " AND (eval_calificacion is null or eval_calificacion = '')";
            }
                
        }        
        $sql = "-- get_pendientes_por_dimension
		$select
                FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                    AND actividades.se_evalua = 'S'
                    AND autoeval_estado = 1 AND eval_estado = 1
                    AND $where $where2 $evaluacion
                $order        
        ";
        if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
             return toba::db()->consultar($sql);
    }    
    
    // devuelve la cantidad de personas de una dimension que no tiene la autoeval confirmada
    function get_no_conf_por_dimension($ciclo,$dimension,$tipo,$personas,$cuenta=1,$departamento,$ubicacion) 
    {
        if ($personas == 1) {
            $distintos = 'DISTINCT';
            $pers_act = 'persona';
        }
        else {
            $distintos = '';
            $pers_act = 'asignacion';
        }
        if($departamento and $ubicacion){
            $where2 = "AND asignaciones.ubicacion = $ubicacion AND asignaciones.departamento = $departamento";
        }
        if ($tipo == 'autoeval') {
            $where = "asignaciones.$pers_act in (SELECT $pers_act FROM asignaciones as asig2 LEFT OUTER JOIN actividades as ac2 ON asig2.actividad = ac2.actividad "
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND autoeval_estado = 1 AND ac2.se_evalua = 'S' AND (autoeval_confirmado = 'N' or autoeval_confirmado is null or autoeval_confirmado = ''))";
        } else {
             $where = "asignaciones.$pers_act in (SELECT $pers_act FROM asignaciones as asig2 LEFT OUTER JOIN actividades as ac2 ON asig2.actividad = ac2.actividad "
                . 'WHERE ciclo_lectivo = '.$ciclo.' AND estado = 15 AND dimension = '.$dimension 
                . " AND eval_estado = 1 AND ac2.se_evalua = 'S' AND (autoeval_confirmado = 'N' or autoeval_confirmado is null or autoeval_confirmado = ''))";          
        }
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
                    	AND $where $where2 
                $order            
        ";
        if ($cuenta == 1) 
             return toba::db()->consultar_fila($sql);
         else
             return toba::db()->consultar($sql);
    }

    function get_notifico_por_dimension($ciclo,$dimension,$personas,$cuenta=1,$departamento,$ubicacion) 
    {
        if($departamento and $ubicacion){
            $where2 = "AND asignaciones.ubicacion = $ubicacion AND asignaciones.departamento = $departamento";
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
        $sql = "-- get_notifico_por_dimension
            $select
            FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
            LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                AND actividades.se_evalua = 'S'
                AND autoeval_estado = 1 AND eval_estado = 1
                AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 
                        WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension 
                            AND autoeval_estado = 1 AND eval_estado = 1
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
        $path = toba::proyecto()->get_www();
        if ($personas == 1)
            $distintos = 'DISTINCT';
        else
            $distintos = '';
        $ficha_personas= "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=1&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c7.count||'</a>'";
        $ficha_pen= "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=2&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c8.count||'</a>'";
        $ficha_pen_conf = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=3&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c9.count||'</a>'";
        $auto_personas = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=4&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c1.count||'</a>'";
        $auto_pen = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=5&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c2.count||'</a>'";
        $auto_pen_conf = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=6&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c3.count||'</a>'";
        $eval_personas = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=7&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c1.count||'</a>'";
        $eval_pen = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=8&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c4.count||'</a>'";
        $eval_pen_conf = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=9&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c5.count||'</a>'";
        $noti_personas = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=10&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c1.count||'</a>'";
        $noti_notificados = "'<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=".$dimension."&columna=11&departamento='||c1.departamento||'&ubicacion='||c1.ubicacion||' target=''_blank''>'||c6.count||'</a>'";
        $sql = "
        SELECT 
            (SELECT codigo FROM ubicaciones WHERE ubicacion = c1.ubicacion) as sede, 
            (SELECT descripcion FROM departamentos WHERE departamento = c1.departamento) as depto, 
          
            ".$ficha_personas." as ficha_personas,
          
            ".$ficha_pen." as ficha_pen,
            round(c8.count::numeric * 100/c7.count::numeric,2) as ficha_pen_porc,
            ".$ficha_pen_conf." as ficha_pen_conf,
            round(c9.count::numeric * 100/c7.count::numeric,2) as ficha_pen_conf_porc,
            ".$auto_personas." as auto_personas, 
            ".$auto_pen." as auto_pen,
            round(c2.count::numeric * 100/c1.count::numeric,2) as auto_pen_porc,
            ".$auto_pen_conf." as auto_pen_conf,
            round(c3.count::numeric * 100/c1.count::numeric,2) as auto_pen_conf_porc,
            ".$eval_personas." as eval_personas,
            ".$eval_pen." as eval_pen,
            round(c4.count::numeric * 100/c1.count::numeric,2) as eval_pen_porc,
            ".$eval_pen_conf." as eval_pen_conf,
            round(c5.count::numeric * 100/c1.count::numeric,2) as eval_pen_conf_porc,
            ".$noti_personas." as noti_personas,
            ".$noti_notificados." as noti_notificados,
            round(c6.count::numeric * 100/c1.count::numeric,2) as noti_notificados_porc

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
            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND autoeval_estado = 1 AND eval_estado = 1
                                AND actividades.se_evalua = 'S'
                                AND asignaciones.asignacion in (SELECT asignacion FROM asignaciones as asig2 LEFT OUTER JOIN actividades as act2 ON asig2.actividad = act2.actividad
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension 
                                AND act2.se_evalua = 'S' AND autoeval_estado = 1 AND (autoeval_calificacion is null or autoeval_calificacion = ''))
            GROUP BY asignaciones.ubicacion, asignaciones.departamento) c2 ON c1.ubicacion = c2.ubicacion AND c1.departamento = c2.departamento

        LEFT JOIN

            (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
                            FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND autoeval_estado = 1 AND eval_estado = 1
                                AND actividades.se_evalua = 'S'
                                AND asignaciones.asignacion in (SELECT asignacion FROM asignaciones as asig2 LEFT OUTER JOIN actividades as act2 ON asig2.actividad = act2.actividad
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                                AND act2.se_evalua = 'S' AND autoeval_estado = 1 AND (autoeval_confirmado = 'N' or autoeval_confirmado = ''))
            GROUP BY asignaciones.ubicacion, asignaciones.departamento) c3 ON c1.ubicacion = c3.ubicacion AND c1.departamento = c3.departamento

        LEFT JOIN

            (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
            FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND autoeval_estado = 1 AND eval_estado = 1
                                AND actividades.se_evalua = 'S'
                                AND asignaciones.asignacion in (SELECT asignacion FROM asignaciones as asig2 LEFT OUTER JOIN actividades as act2 ON asig2.actividad = act2.actividad
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension  
                                AND act2.se_evalua = 'S' AND eval_estado = 1 AND (eval_calificacion is null or eval_calificacion = ''))		
            GROUP BY asignaciones.ubicacion, asignaciones.departamento) c4 ON c1.ubicacion = c4.ubicacion AND c1.departamento = c4.departamento

        LEFT JOIN

            (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
            FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                            LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
           WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension AND autoeval_estado = 1 AND eval_estado = 1
                                AND actividades.se_evalua = 'S'
                                AND asignaciones.asignacion in (SELECT asignacion FROM asignaciones as asig2 LEFT OUTER JOIN actividades as act2 ON asig2.actividad = act2.actividad
                                WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension  
                                AND act2.se_evalua = 'S' AND eval_estado = 1 AND (eval_confirmado = 'N' or eval_confirmado = ''))
            GROUP BY asignaciones.ubicacion, asignaciones.departamento) c5 ON c1.ubicacion = c5.ubicacion AND c1.departamento = c5.departamento

        LEFT JOIN

            (SELECT asignaciones.ubicacion, asignaciones.departamento,COUNT($distintos asignaciones.persona)
            FROM asignaciones LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad
                        LEFT OUTER JOIN personas ON asignaciones.persona = personas.persona
            WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension
                            AND autoeval_estado = 1 AND eval_estado = 1
                            AND actividades.se_evalua = 'S'
                            AND asignaciones.persona not in (SELECT persona FROM asignaciones as asig2 LEFT OUTER JOIN actividades as act2 ON asig2.actividad = act2.actividad
                                    WHERE ciclo_lectivo = $ciclo AND estado = 15 AND dimension = $dimension 
                                    AND act2.se_evalua = 'S' AND eval_notificacion = 'S')
            GROUP BY asignaciones.ubicacion, asignaciones.departamento) c6 ON c1.ubicacion = c6.ubicacion AND c1.departamento = c6.departamento
        
        LEFT JOIN 
        


            (SELECT ubicacion,
                        departamento,
                    COUNT($distintos designaciones.persona)

            FROM 	personas, 
				designaciones
            WHERE  personas.persona = designaciones.persona
				AND designaciones.designacion_tipo = 1 
				AND designaciones.estado in (1,4,5)
				AND designaciones.dimension = $dimension
            GROUP BY designaciones.ubicacion, designaciones.departamento
            ORDER BY ubicacion, departamento) c7 ON c1.ubicacion = c7.ubicacion AND c1.departamento = c7.departamento               
        
        LEFT JOIN 

            (SELECT ubicacion, departamento, COUNT(DISTINCT designaciones.persona)
            FROM 	personas, 
                    designaciones
            WHERE personas.persona not in (SELECT persona FROM autoevaluaciones WHERE ciclo_lectivo = 2018)
                    AND personas.persona = designaciones.persona
                    AND designaciones.designacion_tipo = 1 
                    AND designaciones.estado in (1,4,5)
                    AND dimension = $dimension
            GROUP BY ubicacion, departamento ) c8 ON c1.ubicacion = c8.ubicacion AND c1.departamento = c8.departamento      
        
        LEFT JOIN
        
            (SELECT ubicacion, departamento, sum(cc9.count) as count
             FROM(     
                    SELECT ubicacion, departamento, COUNT(DISTINCT designaciones.persona)

                    FROM personas, 
                        autoevaluaciones LEFT OUTER JOIN designaciones ON (autoevaluaciones.persona = designaciones.persona)
                    WHERE personas.persona = autoevaluaciones.persona 
                        AND autoevaluaciones.confirmado = 'N'
                        AND designaciones.estado in (1,4,5)
                        AND autoevaluaciones.ciclo_lectivo = $ciclo
                        AND designaciones.dimension = $dimension
			GROUP BY ubicacion, departamento 
            UNION
                    SELECT ubicacion, departamento, COUNT(DISTINCT designaciones.persona)
                    FROM 	personas, 
				designaciones
                    WHERE personas.persona not in (SELECT persona FROM autoevaluaciones WHERE ciclo_lectivo = 2018)
				AND personas.persona = designaciones.persona
				AND designaciones.designacion_tipo = 1 
				AND designaciones.estado in (1,4,5)
				AND dimension = $dimension
                        GROUP BY ubicacion, departamento
                    ) as cc9
                    GROUP BY ubicacion, departamento
            ) c9 ON c1.ubicacion = c9.ubicacion AND c1.departamento = c9.departamento
        ";
        return toba::db()->consultar($sql);
    }
}

?>
