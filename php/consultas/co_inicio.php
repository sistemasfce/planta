<?php

class co_inicio
{
    function get_cumpleanios($mes, $dia)
    {
        $sql = "SELECT 	*,
			apellido || ', ' || nombres as nombre_completo
		FROM 	personas
		WHERE   
			(select extract(month FROM fecha_nac)) = $mes
			AND (select extract(day FROM fecha_nac)) = $dia
                            AND personas.estado_docente <> 5
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_activos_sin_designacion()
    {
        $sql = "SELECT  DISTINCT
                        apellido || ', ' || nombres as nombre_completo
                FROM    personas 
                WHERE   persona not in (SELECT des2.persona FROM designaciones as des2 WHERE des2.estado in (1,4,5) and des2.persona = persona)
                        AND personas.estado_docente = 1
        ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }

    function get_vencimientos($desde, $hasta)
    {
	$sql = "
                SELECT asignacion,
                        'Asignacion' as tipo,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        actividades.descripcion as actividad,
                        asignaciones.fecha_desde,
                        asignaciones.fecha_hasta,
                        asignaciones.resolucion || '/' || asignaciones.resolucion_anio as resol_numero,
                        estados.estado,
                        estados.descripcion as estado_desc
                FROM asignaciones, personas, actividades, estados
                WHERE asignaciones.persona = personas.persona
                AND asignaciones.actividad = actividades.actividad
                AND asignaciones.estado = estados.estado
                AND fecha_hasta between '$desde'::date and '$hasta'::date
                AND asignaciones.estado in (1)

                UNION 

                SELECT designacion,
                        'Designacion' as tipo,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        espacios_disciplinares.descripcion as actividad,
                        designaciones.fecha_desde,
                        designaciones.fecha_hasta,
                        designaciones.resolucion || '/' || designaciones.resolucion_anio as resol_numero,
                        estados.estado,
                        estados.descripcion as estado_desc
                FROM designaciones, personas, espacios_disciplinares, estados
                WHERE designaciones.persona = personas.persona
                AND designaciones.espacio_disciplinar = espacios_disciplinares.espacio_disciplinar
                AND designaciones.estado = estados.estado
                AND fecha_hasta between '$desde'::date and '$hasta'::date
                AND designaciones.estado in (1)

                ORDER BY nombre_completo
		";
	return toba::db()->consultar($sql);
    }

    function get_asignaciones_periodo_menor($hasta)
    {
	$sql = "
                SELECT asignacion,
                        'Aasignacion' as tipo,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        actividades.descripcion as actividad,
                        asignaciones.fecha_desde,
                        asignaciones.fecha_hasta,
                        asignaciones.resolucion || '/' || asignaciones.resolucion_anio as resol_numero,
                        estados.estado,
                        estados.descripcion as estado_desc
                FROM asignaciones, personas, actividades, estados
                WHERE asignaciones.persona = personas.persona
                AND asignaciones.actividad = actividades.actividad
                AND asignaciones.estado = estados.estado
                AND fecha_hasta < '$hasta'
                AND asignaciones.estado in (1)

                UNION 

                SELECT designacion,
                        'Designacion' as tipo,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        espacios_disciplinares.descripcion as actividad,
                        designaciones.fecha_desde,
                        designaciones.fecha_hasta,
                        designaciones.resolucion || '/' || designaciones.resolucion_anio as resol_numero,
                        estados.estado,
                        estados.descripcion as estado_desc
                FROM designaciones, personas, espacios_disciplinares, estados
                WHERE designaciones.persona = personas.persona
                AND designaciones.espacio_disciplinar = espacios_disciplinares.espacio_disciplinar
                AND designaciones.estado = estados.estado
                AND fecha_hasta < '$hasta'
                AND designaciones.estado in (1)

                ORDER BY nombre_completo
		";
	return toba::db()->consultar($sql);
    }
}
?>
