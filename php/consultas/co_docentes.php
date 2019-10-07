<?php

class co_docentes
{
    function get_docentes($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT DISTINCT    
                        personas.*,
                        apellido || ', ' || nombres as nombre_completo
                FROM    personas
                WHERE   
                        estado_docente = 1
			AND $where
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }

    function get_docentes_completo($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT DISTINCT
			personas.persona,
			apellido || ', ' || nombres as nombre_completo,
			personas.documento,
			personas.legajo
		FROM 	personas, personas_perfiles
		WHERE   personas.persona = personas_perfiles.persona
		        AND perfil = 1 -- docente
			AND $where
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }

    function get_docentes_x_estado($where)
    {   
        $sql = "SELECT  apellido || ', ' || nombres as nombre_completo, 
			(SELECT fecha FROM personas_perfiles WHERE persona = personas.persona 
				AND perfil = 1 AND perfil_estado = personas.estado_docente ORDER BY fecha DESC LIMIT 1) as fecha,
                        perfiles_estados.descripcion as estado_desc
                FROM    personas, 
                        perfiles_estados
                WHERE   personas.estado_docente = perfiles_estados.perfil_estado
                        AND $where
                ORDER BY nombre_completo
                ";

        return toba::db()->consultar($sql);
    } 

    function get_datos_historicos($where)
    {
	$sql = "SELECT hist_datos_academicos.*,
			personas.apellido || ', ' || personas.nombres as nombre_completo
		FROM hist_datos_academicos LEFT OUTER JOIN personas ON (hist_datos_academicos.persona = personas.persona)
		WHERE $where
		";
        return toba::db()->consultar($sql);
    }

    function get_docentes_a_definir()
    {
	$sql = "
SELECT apellido || ', ' || nombres as nombre_completo,
	(SELECT SUM(carga_horaria) FROM asignaciones WHERE personas.persona = asignaciones.persona
		AND asignaciones.estado = 1
		AND asignaciones.actividad = 347) as a_definir,


            (SELECT sum(carga_horaria_dedicacion::Int) FROM designaciones WHERE designaciones.persona = personas.persona
            AND designaciones.estado in (1,4,5) AND designaciones.designacion_tipo = 1) as horas_desig,
 
            (SELECT sum(carga_horaria_dedicacion::Int) FROM designaciones WHERE designaciones.persona = personas.persona
            AND designaciones.estado = 6 AND designaciones.designacion_tipo in (2,3)
            and exists 
                        (SELECT designacion FROM designaciones as dd2 WHERE dd2.estado in (1,4,5) AND dd2.designacion_tipo = 1 
                        AND dd2.persona = personas.persona AND dd2.designacion = designaciones.designacion_padre)        
            ) as horas_licenciadas,
 
            (SELECT sum(carga_horaria::Int) FROM asignaciones WHERE asignaciones.persona = personas.persona
            AND asignaciones.estado = 1) as horas_asignadas,
            (SELECT SUM(carga_horaria) FROM asignaciones WHERE persona = personas.persona AND asignaciones.estado = 1) as horas_asig
	
   FROM personas, personas_perfiles
   
        WHERE personas.persona = personas_perfiles.persona
                        AND perfil = 1 -- docente
                        AND 1 = (SELECT pp.perfil_estado FROM personas_perfiles as pp WHERE
                                 pp.persona = personas.persona 
                                 AND pp.perfil = 1 ORDER BY pp.fecha, pp.persona_perfil DESC LIMIT 1)                               
        ORDER BY nombre_completo
		";
        return toba::db()->consultar($sql);
    }

}

?>
