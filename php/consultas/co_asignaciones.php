<?php

class co_asignaciones
{

    function get_asignaciones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "
	SELECT  asignaciones.asignacion,
                        personas.documento,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        actividades.descripcion as actividad_desc,
                        departamentos.descripcion as departamento_desc,
                        categorias.codigo as categoria_desc,
                        asignaciones.carga_horaria,
                        ubicaciones.codigo as ubicacion_desc,
			dimensiones.codigo as dimension_desc,
                        fecha_desde,
                        fecha_hasta,
                        resolucion,
                        resolucion_fecha,
                        resolucion_anio,
                        resolucion || '/' || resolucion_anio || ' ' || resoluciones_tipos.descripcion as resolucion_desc,
                        carrera_academica,
			responsable,
                        asignaciones.observaciones,
                        asignaciones.estado,
                        estados.descripcion as estado_desc
                FROM    asignaciones LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
                        LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension),  
                        actividades, 
                        personas,
                        resoluciones_tipos,
                        categorias,
                        ubicaciones,
                        estados
                WHERE   asignaciones.persona = personas.persona
                        AND asignaciones.actividad = actividades.actividad
                        AND asignaciones.rol = categorias.categoria
                        AND asignaciones.resolucion_tipo = resoluciones_tipos.resolucion_tipo
                        AND asignaciones.ubicacion = ubicaciones.ubicacion
                        AND asignaciones.estado = estados.estado
			AND $where
                ORDER BY nombre_completo, asignaciones.resolucion_fecha::date DESC, actividades.descripcion
        ";
	return toba::db()->consultar($sql);
   }

    function get_asignaciones_de_persona($where, $mostrar_historico)
    {
        if ($mostrar_historico) { // si quiero ver el historico...
                $hist = "1=1";
        } else {
                $hist = " asignaciones.estado = 1";
        }

        $sql = "
	SELECT  asignaciones.asignacion,
		asignaciones.designacion,
                        personas.documento,
                        personas.apellido || ', ' || personas.nombres as nombre_completo,
                        actividades.descripcion as actividad_desc,
                        departamentos.descripcion as departamento_desc,
                        categorias.codigo as rol_desc,
                        asignaciones.carga_horaria,
                        ubicaciones.codigo as ubicacion_desc,
			dimensiones.codigo as dimension_desc,
			designaciones.designacion,
			designaciones.resolucion || '/' || designaciones.resolucion_anio as resol_designacion,
                        asignaciones.fecha_desde,
                        asignaciones.fecha_hasta,
			asignaciones.responsable,
                        asignaciones.resolucion,
                        asignaciones.resolucion_fecha,
                        asignaciones.resolucion_anio,
                        asignaciones.resolucion || '/' || asignaciones.resolucion_anio || ' ' || resoluciones_tipos.descripcion as resolucion_desc,
                        asignaciones.carrera_academica,
                        asignaciones.observaciones,
                        asignaciones.estado,
                        estados.descripcion as estado_desc

                FROM    asignaciones LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
			LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
			LEFT OUTER JOIN designaciones ON (asignaciones.designacion = designaciones.designacion),  
                        actividades, 
                        personas,
                        resoluciones_tipos,
                        categorias,
                        ubicaciones,
                        estados
                WHERE   asignaciones.persona = personas.persona
                        AND asignaciones.actividad = actividades.actividad
                        AND asignaciones.rol = categorias.categoria
                        AND asignaciones.resolucion_tipo = resoluciones_tipos.resolucion_tipo
                        AND asignaciones.ubicacion = ubicaciones.ubicacion
                        AND asignaciones.estado = estados.estado
		--	AND asignaciones.persona = $persona
			AND $where
			AND $hist
                ORDER BY asignaciones.resolucion_fecha::date DESC, actividad_desc
        ";
	return toba::db()->consultar($sql);
   }

   // obtener la suma de horas asignadas activas de una designacion
   function get_horas_asignadas_x_designacion($designacion)
   {
	$sql = "
		SELECT sum(carga_horaria) as suma
		FROM asignaciones
		WHERE designacion = $designacion AND estado = 1 
		";
	return toba::db()->consultar_fila($sql);
   }  


   function get_puntajes_por_dimension()
   {
	$sql = "
	SELECT 	asignaciones.ubicacion, 
		asignaciones.dimension, 
		ubicaciones.descripcion as ubicacion_desc, 
		dimensiones.descripcion as dimension_desc, 
	        sum(carga_horaria) as total, sum(carga_horaria) / 440::Real as div_simple, 
	        count (DISTINCT personas.persona) as personas
 
	FROM asignaciones LEFT OUTER JOIN personas ON (asignaciones.persona = personas.persona)
		LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
	        LEFT OUTER JOIN dimensiones ON (asignaciones.dimension = dimensiones.dimension)
	        LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
	        LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
	        LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
	        LEFT OUTER JOIN estados ON (asignaciones.estado = estados.estado)
	        LEFT OUTER JOIN resoluciones_tipos ON (asignaciones.resolucion_tipo = resoluciones_tipos.resolucion_tipo)
	WHERE asignaciones.estado = 1 

	GROUP BY asignaciones.ubicacion, asignaciones.dimension, dimensiones.descripcion, ubicaciones.descripcion
		";
	return toba::db()->consultar($sql);
   }

   function get_asignacion_tabla($asignacion)
   {
	$sql = "SELECT *
		FROM asignaciones
		WHERE asignacion = $asignacion
		";
	return toba::db()->consultar_fila($sql);
   }


   function get_personas_por_actividad($actividad,$ubicacion)
   {
	$sql = "SELECT
			personas.apellido || ', ' || personas.nombres as nombre_completo,
			actividades.descripcion as actividad_desc,
			ubicaciones.codigo as ubicacion_desc,
			categorias.descripcion as rol_desc,
			responsable,
			carrera_academica
		FROM 	personas, 
			asignaciones LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
			LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
		WHERE asignaciones.persona = personas.persona
		AND asignaciones.actividad = $actividad
		AND asignaciones.ubicacion = $ubicacion
		AND asignaciones.estado = 1
		";
	return toba::db()->consultar($sql);
   }

   function get_planta_docente($where)
   {
	$sql = "
	SELECT	    asignaciones.ubicacion,
            ubicaciones.descripcion as ubicacion_desc,
	    asignaciones.departamento,
	    departamentos.descripcion as departamento_desc,
	    personas.persona,
            personas.apellido || ', ' || personas.nombres as nombre_completo,
            actividades.descripcion as actividad_desc,
            categorias.codigo as rol_desc,
            responsable,
            carrera_academica
        FROM     personas, 
            asignaciones LEFT OUTER JOIN actividades ON (asignaciones.actividad = actividades.actividad)
            LEFT OUTER JOIN ubicaciones ON (asignaciones.ubicacion = ubicaciones.ubicacion)
            LEFT OUTER JOIN departamentos ON (asignaciones.departamento = departamentos.departamento)
            LEFT OUTER JOIN categorias ON (asignaciones.rol = categorias.categoria)
        WHERE asignaciones.persona = personas.persona
        AND actividades.ambito = 1
        AND asignaciones.estado = 1
	AND $where
	ORDER BY actividad_desc, nombre_completo
		";
	return toba::db()->consultar($sql);
   }

   function get_rad($ubicacion)
   {
	$sql = "
		SELECT ubicacion_desc, tipo_doc, documento, nombre_completo, 
			codigo, descripcion, to_char(fecha_hasta,'DD/MM/YYYY') as fecha 
		FROM vista_rad 
		WHERE ubicacion = $ubicacion
		";
	return toba::db()->consultar($sql);

   }

   function get_puntajes_por_actividad($codigo,$ubicacion,$persona)
   {
	$sql = "
    SELECT DISTINCT 
                personas.documento, 
                personas.apellido || ', ' || personas.nombres as nombre_completo,
                actividades.descripcion, 
                departamentos.descripcion AS departamento, 
                ubicaciones.codigo AS sede, 
                categorias.codigo AS categoria,
                (SELECT SUM(des2.carga_horaria_dedicacion::int) FROM designaciones as des2 WHERE des2.persona = asignaciones.persona AND des2.estado = 1) * 44 as suma_activas,
                (SELECT des1.carga_horaria::Int - des2.carga_horaria::Int FROM designaciones as des2, designaciones as des1 
                        WHERE des2.persona = asignaciones.persona AND des1.persona = asignaciones.persona
                        AND (des2.designacion_tipo = 2 AND des2.estado = 1) and des1.estado = 7) * 44 as horas_licencia,
                asignaciones.carga_horaria as horas_asignadas,

                        (SELECT SUM(cargos.puntaje::Real) FROM cargos, designaciones as des2 
                        WHERE des2.categoria = cargos.categoria AND des2.dedicacion = cargos.dedicacion
                        AND des2.persona = asignaciones.persona AND des2.estado = 1) as puntaje_liquidacion_activos,
                        
                        (SELECT cargos.puntaje::Real - cargos2.puntaje::Real FROM cargos, cargos as cargos2, designaciones as des2, designaciones as des1
                        WHERE des1.categoria = cargos.categoria AND des1.dedicacion = cargos.dedicacion
                        AND des2.categoria = cargos2.categoria AND des2.dedicacion = cargos2.dedicacion
                        AND des2.persona = asignaciones.persona AND des1.persona = asignaciones.persona 
                        AND (des2.designacion_tipo = 2 AND des2.estado = 1) and des1.estado = 7) as puntaje_liquidacion_licencia,

                        (SELECT SUM(cargos.puntaje_dedicacion::Real) FROM cargos, designaciones as des2 
                        WHERE des2.categoria = cargos.categoria AND des2.dedicacion = cargos.dedicacion
                        AND des2.persona = asignaciones.persona AND des2.estado = 1) as puntaje_dedicacion_activos,

                        (SELECT cargos.puntaje_dedicacion::Real - cargos2.puntaje_dedicacion::Real FROM cargos, cargos as cargos2, designaciones as des2, designaciones as des1
                        WHERE des1.categoria = cargos.categoria AND des1.dedicacion = cargos.dedicacion
                        AND des2.categoria = cargos2.categoria AND des2.dedicacion = cargos2.dedicacion
                        AND des2.persona = asignaciones.persona AND des1.persona = asignaciones.persona 
                        AND (des2.designacion_tipo = 2 AND des2.estado = 1) and des1.estado = 7) as puntaje_dedicacion_licencia


        FROM asignaciones
                LEFT JOIN personas ON asignaciones.persona = personas.persona
                LEFT JOIN ubicaciones ON asignaciones.ubicacion = ubicaciones.ubicacion
                LEFT JOIN categorias ON asignaciones.rol = categorias.categoria
                LEFT JOIN departamentos ON asignaciones.departamento = departamentos.departamento
                LEFT OUTER JOIN actividades ON asignaciones.actividad = actividades.actividad 
                
        WHERE   asignaciones.persona = personas.persona 
                AND actividades.codigo::text = '$codigo'::text 
                AND asignaciones.ubicacion = $ubicacion
                AND asignaciones.estado = 1
                AND asignaciones.persona = $persona
		";
	return toba::db()->consultar($sql);
   }
}
?>
