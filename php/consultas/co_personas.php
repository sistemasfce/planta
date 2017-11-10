<?php
 
class co_personas
{
    function get_personas($where='1=1')
    {
        $sql = "SELECT *, 
			personas.apellido || ', ' || personas.nombres as nombre_completo,
			date_part('year',age(fecha_nac)) as edad, 
			(SELECT descripcion FROM perfiles_estados WHERE 
                        	perfiles_estados.perfil_estado = personas.estado_docente) as estado_docente_desc,
			(SELECT descripcion FROM perfiles_estados WHERE 
                                perfiles_estados.perfil_estado = personas.estado_nodocente) as estado_nodocente_desc,
                        (SELECT descripcion FROM perfiles_estados WHERE 
                                perfiles_estados.perfil_estado = personas.estado_externo) as estado_externo_desc
		FROM personas
		WHERE $where
		ORDER BY apellido, nombres
        ";
	return toba::db()->consultar($sql);
    }

    function get_id($documento)
    {
	$sql = "SELECT persona
		FROM personas
		WHERE documento = '$documento'
		";
	return toba::db()->consultar_fila($sql);
    }

    function get_personas_titulos()
    {
        $sql = "SELECT 	personas_titulos.persona_titulo,
			personas.documento, 
			personas.apellido || ', ' || personas.nombres as nombre_completo,
			titulos.descripcion as titulo_desc,
			titulos.alcance,
			instituciones.descripcion as institucion_desc,
			personas_titulos.anio
		FROM personas_titulos, personas, titulos, instituciones
		WHERE personas_titulos.persona = personas.persona
			AND personas_titulos.titulo = titulos.titulo
			AND personas_titulos.institucion = instituciones.institucion
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }
/*
    function get_perfiles_persona($persona)
    {
	$sql = "
	SELECT
        -- docente
            (SELECT perfil_estado FROM personas_perfiles WHERE persona = personas.persona
            AND perfil = 1 ORDER BY persona_perfil DESC LIMIT 1) as perfil_docente_estado,
        (SELECT descripcion FROM perfiles_estados, personas_perfiles WHERE 
            perfiles_estados.perfil_estado = personas_perfiles.perfil_estado
                    AND personas_perfiles.persona = personas.persona
            AND personas_perfiles.perfil = 1 ORDER BY persona_perfil DESC LIMIT 1) as perfil_docente_estado_desc,    
        -- No docente
            (SELECT perfil_estado FROM personas_perfiles WHERE persona = personas.persona
            AND perfil = 2 ORDER BY persona_perfil DESC LIMIT 1) as perfil_no_docente_estado,
        (SELECT descripcion FROM perfiles_estados, personas_perfiles WHERE 
            perfiles_estados.perfil_estado = personas_perfiles.perfil_estado
                    AND personas_perfiles.persona = personas.persona
            AND personas_perfiles.perfil = 2 ORDER BY persona_perfil DESC LIMIT 1) as perfil_no_docente_estado_desc,    
        -- externo
            (SELECT perfil_estado FROM personas_perfiles WHERE persona = personas.persona
            AND perfil = 3 ORDER BY persona_perfil DESC LIMIT 1) as perfil_externo_estado,
        (SELECT descripcion FROM perfiles_estados, personas_perfiles WHERE 
            perfiles_estados.perfil_estado = personas_perfiles.perfil_estado
                    AND personas_perfiles.persona = personas.persona
            AND personas_perfiles.perfil = 3 ORDER BY persona_perfil DESC LIMIT 1) as perfil_externo_estado_desc

            FROM personas
            WHERE persona = $persona
		";
	return toba::db()->consultar_fila($sql);
    }
*/

    // devuelve los datos de contacto de todas las personas activas y de un perfil dado por parametro
    function get_datos_contacto($perfil)
    {
	$sql = "
		SELECT documento,	
			apellido || ', ' || nombres as nombre_completo,
			(SELECT nombre FROM mug_localidades WHERE localidad = personas.localidad) as localidad,
			email,
			email_2,
			telefono,
			telefono_2
		FROM personas, personas_perfiles
		WHERE personas.persona = personas_perfiles.persona
			AND personas_perfiles.perfil = $perfil
                     	AND 1 = (SELECT pp.perfil_estado FROM personas_perfiles as pp WHERE
                        	pp.persona = personas.persona 
                                AND pp.perfil = $perfil ORDER BY pp.fecha, pp.persona_perfil DESC LIMIT 1) 
		ORDER BY nombre_completo
		";

	return toba::db()->consultar($sql);
    }

    // devuelve el nombre completo de una persona
    function get_persona_nombre($where)
    {
	$sql = "SELECT apellido || ', ' || nombres as nombre_completo
		FROM personas
		WHERE $where
		";
	return toba::db()->consultar_fila($sql);
    }

    // devuelve todos los datos de una persona dado su id
    function get_datos_persona($persona)
    {
	$sql = "SELECT *,
			apellido || ', ' || nombres as nombre_completo
		FROM personas
		WHERE persona = $persona
		";
	return toba::db()->consultar_fila($sql);
    }

    function get_persona_por_asignacion($asignacion)
    {
	$sql = "SELECT *
		FROM personas, asignaciones
		WHERE personas.persona = asignaciones.persona
			AND asignaciones.asignacion = $asignacion
		";
	return toba::db()->consultar_fila($sql);
    }

    function actualizar_clave($documento, $clave)
    {
	$sql = "UPDATE personas
		SET cambio_clave = 'S', clave = '$clave'
		WHERE documento = '$documento'
		";
	return toba::db()->consultar($sql);
    }
}
?>
