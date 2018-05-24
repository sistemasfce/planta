<?php

class co_designaciones
{

    function get_designacion_tabla($designacion) 
    {
	$sql = "SELECT *
		FROM designaciones
		WHERE designacion = $designacion
		";
	return toba::db()->consultar_fila($sql);
    }

    function get_designaciones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT  designaciones.designacion,
                        personas.persona,
                        designaciones.designacion_padre,
			personas.documento,
			personas.apellido || ', ' || personas.nombres as nombre_completo,
			espacios_disciplinares.descripcion as espacio_disciplinar_desc,
			departamentos.descripcion as departamento_desc,
			categorias.codigo as categoria_desc,
			dedicaciones.codigo as dedicacion_desc,
			designaciones.carga_horaria,
			designaciones.carga_horaria::Int * 44 as carga_horaria_dedicacion,
			caracteres.codigo as caracter_desc,
			ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc,
			--fecha_desde,
			COALESCE (fecha_desde_jubilacion, fecha_desde) as fecha_desde,
			fecha_hasta,
			resolucion,
			resolucion_fecha,
			resolucion_anio,
			resolucion || '/' || resolucion_anio || ' ' || resoluciones_tipos.descripcion as resolucion_desc,
			ratif_resolucion || '/' || ratif_resolucion_anio || ' ' || resoluciones_tipos2.descripcion as ratif_resolucion_desc,
			carrera_academica,
			designaciones.observaciones,
			designaciones.designacion_tipo,
			designaciones_tipos.descripcion as designacion_tipo_desc,
			designaciones.estado,
			estados.descripcion as estado_desc,
			(SELECT resolucion || '/' || resolucion_anio || ' ' || resoluciones_tipos.descripcion as resolucion_padre 
				FROM designaciones as d2, resoluciones_tipos 
				WHERE d2.resolucion_tipo = resoluciones_tipos.resolucion_tipo AND d2.designacion = designaciones.designacion_padre) as resolucion_padre
		FROM 	designaciones LEFT OUTER JOIN resoluciones_tipos as resoluciones_tipos2 ON (designaciones.ratif_resolucion_tipo = resoluciones_tipos2.resolucion_tipo)
			LEFT OUTER JOIN designaciones_tipos ON (designaciones.designacion_tipo = designaciones_tipos.designacion_tipo )
			LEFT OUTER JOIN espacios_disciplinares ON (designaciones.espacio_disciplinar = espacios_disciplinares.espacio_disciplinar)
			LEFT OUTER JOIN dedicaciones ON (designaciones.dedicacion = dedicaciones.dedicacion)
			LEFT OUTER JOIN personas ON (designaciones.persona = personas.persona)
			LEFT OUTER JOIN departamentos ON (designaciones.departamento = departamentos.departamento)
			LEFT OUTER JOIN resoluciones_tipos ON (designaciones.resolucion_tipo = resoluciones_tipos.resolucion_tipo)
			LEFT OUTER JOIN categorias ON (designaciones.categoria = categorias.categoria)
			LEFT OUTER JOIN caracteres ON (designaciones.caracter = caracteres.caracter)
			LEFT OUTER JOIN ubicaciones ON (designaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN estados ON (designaciones.estado = estados.estado)
                        LEFT OUTER JOIN dimensiones ON (designaciones.dimension = dimensiones.dimension)
		WHERE 	

		$where
		ORDER BY nombre_completo, designaciones.resolucion_fecha::date DESC, espacios_disciplinares.descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_designaciones_de_persona($where,$mostrar_historico=null)
    {
	if (!$mostrar_historico) {
		$where_estado = " designaciones.estado in (1,5)";
		// estado 3 = historico, 4 = licencia total, 15 = finalizada
	} else {
		$where_estado = "1=1";
	}
	$sql = "
		SELECT  designaciones.designacion,
                        personas.documento,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        espacios_disciplinares.descripcion as espacio_disciplinar_desc,
                        departamentos.descripcion as departamento_desc,
                        categorias.codigo as categoria_desc,
                        dedicaciones.codigo as dedicacion_desc,
                        designaciones.carga_horaria,
                        designaciones.carga_horaria::Int * 44 as carga_horaria_dedicacion,
                        caracteres.codigo as caracter_desc,
                        ubicaciones.codigo as ubicacion_desc,
                        dimensiones.codigo as dimension_desc,
                        --fecha_desde,
			COALESCE (fecha_desde_jubilacion, fecha_desde) as fecha_desde,
                        fecha_hasta,
                        resolucion,
                        resolucion_fecha,
                        resolucion_anio,
                        resolucion || '/' || resolucion_anio || ' ' || resoluciones_tipos.descripcion as resolucion_desc,
                        ratif_resolucion || '/' || ratif_resolucion_anio as ratif_resolucion_desc,
                        carrera_academica,
                        designaciones.observaciones,
                        designaciones.designacion_tipo,
                        designaciones_tipos.descripcion as designacion_tipo_desc,
                        designaciones.estado,
                        estados.descripcion as estado_desc,
			(SELECT resolucion || '/' || resolucion_anio || ' ' || resoluciones_tipos.descripcion as resolucion_padre 
				FROM designaciones as d2, resoluciones_tipos 
				WHERE d2.resolucion_tipo = resoluciones_tipos.resolucion_tipo AND d2.designacion = designaciones.designacion_padre) as resolucion_padre

                FROM    designaciones LEFT OUTER JOIN dimensiones ON (designaciones.dimension = dimensiones.dimension), 
                        designaciones_tipos,
                        espacios_disciplinares, 
                        dedicaciones, 
                        personas,
                        departamentos,
                        resoluciones_tipos,
                        categorias,
                        caracteres,
                        ubicaciones,
                        estados
                WHERE   designaciones.persona = personas.persona
                        AND designaciones.espacio_disciplinar = espacios_disciplinares.espacio_disciplinar
                        AND designaciones.designacion_tipo = designaciones_tipos.designacion_tipo 
                        AND designaciones.dedicacion = dedicaciones.dedicacion
                        AND designaciones.categoria = categorias.categoria
                        AND designaciones.resolucion_tipo = resoluciones_tipos.resolucion_tipo
                        AND designaciones.caracter = caracteres.caracter
                        AND designaciones.ubicacion = ubicaciones.ubicacion
                        AND designaciones.departamento = departamentos.departamento
                        AND designaciones.estado = estados.estado

		
		AND $where
		AND $where_estado
		ORDER BY designaciones.resolucion_fecha::date DESC, espacios_disciplinares.descripcion
		";
	return toba::db()->consultar($sql);
    }	

    function get_horas_licencias_activas($designacion)
    {
	$sql = "SELECT SUM (carga_horaria_dedicacion::Int) as total
		FROM designaciones
		WHERE 	designacion_padre = $designacion 
			AND designacion_tipo in (2,3)
			AND estado = 6
		";
	return toba::db()->consultar_fila($sql);
    }

    function get_horas_neto($where)
    {
        $ciclo = date('Y');
	$sql = "
            SELECT apellido || ', ' || nombres as nombre_completo,
                (SELECT sum(carga_horaria_dedicacion::Int) FROM designaciones WHERE designaciones.persona = personas.persona
                AND designaciones.estado in (1,4,5) AND designaciones.designacion_tipo = 1) as horas_desig,
                (SELECT sum(carga_horaria_dedicacion::Int) FROM designaciones WHERE designaciones.persona = personas.persona
                AND designaciones.estado = 6 AND designaciones.designacion_tipo in (2,3)
                and exists 
                    (SELECT designacion FROM designaciones as dd2 WHERE dd2.estado in (1,4,5) AND dd2.designacion_tipo = 1 
                    AND dd2.persona = personas.persona)		
                ) as horas_licenciadas,
                (SELECT sum(carga_horaria::Int) FROM asignaciones WHERE asignaciones.persona = personas.persona
                AND asignaciones.estado = 1 OR (asignaciones.persona = personas.persona AND asignaciones.estado = 15 AND asignaciones.ciclo_lectivo = $ciclo)) as horas_asignadas
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

    function get_puntajes($where)
    {
	$sql = "
		SELECT designaciones.ubicacion,
                designaciones.departamento, 
                designaciones.dedicacion, 
                designaciones.categoria,
                ubicaciones.descripcion as ubicacion_desc,
                departamentos.descripcion as departamento_desc,
                dedicaciones.descripcion as dedicacion_desc,
                categorias.descripcion as categoria_desc,
                sum (puntaje_dedicacion::real) as puntaje_dedicacion,
                sum (puntaje::real) as puntaje
    
            FROM cargos, 
                categorias,
                dedicaciones,
                designaciones LEFT OUTER JOIN departamentos ON (designaciones.departamento = departamentos.departamento),
                ubicaciones
 
            WHERE     (designaciones.categoria = cargos.categoria AND designaciones.dedicacion = cargos.dedicacion)
                AND cargos.categoria = categorias.categoria
                AND cargos.dedicacion = dedicaciones.dedicacion
                AND designaciones.ubicacion = ubicaciones.ubicacion
                AND designaciones.estado in (1)
                AND $where
 
            GROUP BY 
                ubicacion_desc, 
                departamento_desc, 
                dedicacion_desc, 
                categoria_desc, 
                designaciones.ubicacion, 
                designaciones.departamento, 
                designaciones.dedicacion, 
                designaciones.categoria

            ORDER BY 
                designaciones.ubicacion, 
                designaciones.departamento,
                designaciones.dedicacion, 
                designaciones.categoria
		";
	return toba::db()->consultar($sql);
    }

    function get_padron_profesores_regulares($where)
    {
	$sql = "
		SELECT DISTINCT
			--personas.apellido || ', ' || personas.nombres as nombre_completo,
			personas.apellido,
			personas.nombres,
			personas.tipo_doc,
			personas.documento,
			ubicaciones.ubicacion,
			ubicaciones.descripcion as ubicacion_desc
		FROM 	personas, designaciones LEFT OUTER JOIN ubicaciones ON (designaciones.ubicacion = ubicaciones.ubicacion)
		WHERE 	designaciones.persona = personas.persona
			AND designaciones.estado in (1,4,5)
			AND designaciones.categoria in (1,2,3)
			AND designaciones.caracter = 2
			AND $where
		ORDER BY apellido,nombres --nombre_completo
		";
	return toba::db()->consultar($sql);
    }

    function get_padron_auxiliares_regulares($where)
    {
	$sql = "
		SELECT DISTINCT
			--personas.apellido || ', ' || personas.nombres as nombre_completo,
			personas.apellido,
			personas.nombres,
			personas.tipo_doc,
			personas.documento,
			ubicaciones.ubicacion,
			ubicaciones.descripcion as ubicacion_desc
		FROM personas, designaciones LEFT OUTER JOIN ubicaciones ON (designaciones.ubicacion = ubicaciones.ubicacion)
		WHERE 	designaciones.persona = personas.persona
			AND designaciones.estado in (1,4,5)
			AND designaciones.categoria in (4,5)
			AND designaciones.caracter = 2
			AND $where
			AND personas.persona not in (
				SELECT DISTINCT
					per.persona
				FROM personas as per, designaciones as des
				WHERE des.persona = per.persona
					AND des.estado in (1,4,5)
					AND des.categoria in (1,2,3)
					AND des.caracter = 2
				)
			ORDER BY apellido,nombres --nombre_completo
		";
	return toba::db()->consultar($sql);
    }

    function get_padron_profesores_interinos($where)
    {
	$sql = "
SELECT DISTINCT
                        --personas.apellido || ', ' || personas.nombres as nombre_completo,
                        personas.apellido,
                        personas.nombres,
                        --personas.tipo_doc,
                        personas.documento,
                        ubicaciones.ubicacion,
                        ubicaciones.descripcion as ubicacion_desc
                FROM personas, designaciones LEFT OUTER JOIN ubicaciones ON (designaciones.ubicacion = ubicaciones.ubicacion)
                WHERE designaciones.persona = personas.persona
                        AND designaciones.estado in (1,4,5) -- activo, licencia parcial, total
                        AND designaciones.categoria in (1,2,3) -- titular, asociado, adjunto
                        AND designaciones.caracter = 1 -- interino
			AND designaciones.carrera_academica = 'S'
			AND $where
                        AND personas.persona not in (SELECT DISTINCT
                                per.persona
                                FROM personas as per, designaciones as des
                                WHERE des.persona = per.persona
                                AND des.estado in (1,4,5) -- activo, licencia parcial, total
                                AND des.caracter = 2 -- regular
                        )
UNION

              SELECT DISTINCT
                        --personas.apellido || ', ' || personas.nombres as nombre_completo,
                        personas.apellido,
                        personas.nombres,
                        --personas.tipo_doc,
                        personas.documento,
                        ubicaciones.ubicacion,
                        ubicaciones.descripcion as ubicacion_desc
                FROM personas, designaciones LEFT OUTER JOIN ubicaciones ON (designaciones.ubicacion = ubicaciones.ubicacion)
                WHERE designaciones.persona = personas.persona
                        AND designaciones.estado in (1,4,5) -- activo, licencia parcial, total
                        AND designaciones.categoria in (1,2,3) -- titular, asociado, adjunto
                        AND designaciones.caracter = 1 -- interino
			AND designaciones.carrera_academica = 'N'
			AND $where
                        AND personas.persona not in (SELECT DISTINCT
                                per.persona
                                FROM personas as per, designaciones as des
                                WHERE des.persona = per.persona
                                AND des.estado in (1,4,5) -- activo, licencia parcial, total
                                AND des.caracter = 2 -- regular
				AND $where
                        )

		ORDER BY apellido,nombres --nombre_completo
";
/*
	                AND personas.persona in (SELECT DISTINCT
                                per2.persona
                                FROM personas as per2, designaciones as des2
                                WHERE des2.persona = per2.persona
                                --AND des.estado in (1,4,5) -- activo, licencia parcial, total
                                AND des2.caracter = 1 -- interino
                                AND des2.fecha_desde > '2016-01-01' 
                                AND des2.carrera_academica = 'N'
                        )

	                AND personas.persona not in (SELECT DISTINCT
                                per3.persona
                                FROM personas as per3, hist_datos_academicos as hda
                                WHERE hda.persona = per3.persona
                                --AND des.estado in (1,4,5) -- activo, licencia parcial, total
                                AND caracter_desc = 'I' -- interino
                                AND hda.vigencia_desde > '2015-01-01' 
                                AND hda.carrera_academica = 'N'
                        )
                        
		ORDER BY apellido,nombres --nombre_completo
		";
*/
	return toba::db()->consultar($sql);	
    }

    function get_padron_auxiliares_interinos($where)
    {
	$sql = "
		SELECT DISTINCT
			--personas.apellido || ', ' || personas.nombres as nombre_completo,
			personas.apellido,
			personas.nombres,
			--personas.tipo_doc,
			personas.documento,
			ubicaciones.ubicacion,
			ubicaciones.descripcion as ubicacion_desc
		FROM personas, designaciones LEFT OUTER JOIN ubicaciones ON (designaciones.ubicacion = ubicaciones.ubicacion)
		WHERE designaciones.persona = personas.persona
			AND designaciones.estado in (1,4,5)
			AND designaciones.categoria in (4,5)
			AND designaciones.caracter = 1 
			AND $where
			AND personas.persona not in (SELECT DISTINCT
				per.persona
				FROM personas as per, designaciones as des
				WHERE des.persona = per.persona
				AND des.estado in (1,4,5)
				AND des.caracter = 2
			)
			AND personas.persona not in (SELECT DISTINCT
				per2.persona
				FROM personas as per2, designaciones as des2
				WHERE des2.persona = per2.persona
				AND des2.estado in (1,4,5)
				AND des2.categoria in (1,2,3)
				AND des2.caracter = 1
			)	

		ORDER BY apellido,nombres --nombre_completo
		";
	return toba::db()->consultar($sql);	
    }

    function get_designaciones_sin_ratificar()
    {
	$sql = "
		SELECT designacion,
		    personas.apellido || ', ' || personas.nombres as nombre_completo,
		    espacios_disciplinares.descripcion as actividad,
		    designaciones.fecha_desde,
		    designaciones.fecha_hasta,
		    designaciones.resolucion || '/' || designaciones.resolucion_anio as resol_numero,
		    designaciones_tipos.descripcion as tipo,
		    estados.estado,
		    estados.descripcion as estado_desc
		FROM designaciones, personas, espacios_disciplinares, estados, designaciones_tipos
		WHERE designaciones.persona = personas.persona
			AND designaciones.espacio_disciplinar = espacios_disciplinares.espacio_disciplinar
			AND designaciones.designacion_tipo = designaciones_tipos.designacion_tipo
			AND designaciones.estado = estados.estado
			AND designaciones.resolucion_tipo = 1
			AND designaciones.ratif_resolucion is null
			AND designaciones.estado in (1,6)
		";
	return toba::db()->consultar($sql);	
    }
}
?>
