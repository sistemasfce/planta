<?php
 
class co_parametros
{

    function get_parametros($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM parametros
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_parametro_valor($par)
    {
	$sql = "SELECT *
		FROM parametros
		WHERE parametro = '$par'
		";
	return toba::db()->consultar_fila($sql);
    }

    function get_ciclos_lectivos()
    {
	$sql = "SELECT *
		FROM ciclos_lectivos
		";
	return toba::db()->consultar($sql);
    }

    function get_ubicaciones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM ubicaciones
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_ubicacion_nombre($ubicacion)
    {
 	$sql = "SELECT descripcion
		FROM ubicaciones
		WHERE ubicacion = $ubicacion
		"; 
	return toba::db()->consultar_fila($sql);
    }

    function get_actividades($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT 	actividades.*, 
			(SELECT substring(actividades.descripcion from 1 for 100)) as nombre_corto,
			departamentos.descripcion as departamento_desc,
			ambitos.descripcion as ambito_desc
		FROM actividades 
			LEFT OUTER JOIN departamentos ON (actividades.departamento = departamentos.departamento)
			LEFT OUTER JOIN ambitos ON (actividades.ambito = ambitos.ambito)
		WHERE $where
		ORDER BY actividades.descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_actividades_catedras($where)
    {
	$sql = "SELECT DISTINCT actividades.*
		FROM actividades, asignaciones
		WHERE 	asignaciones.actividad = actividades.actividad
			AND asignaciones.estado = 1
			AND actividades.ambito = 1
			AND activo = 'S'
			AND $where
		";
	return toba::db()->consultar($sql);
    }

    function get_actividades_a_evaluar($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *, actividades.descripcion as act_evaluador, 
			act2.descripcion as act_evaluado,
			ubicaciones.codigo as ubicacion_evaluador,
			ubicaciones2.codigo as ubicacion_evaluado
		FROM actividades_a_evaluar, actividades, actividades as act2, ubicaciones, ubicaciones as ubicaciones2
		WHERE actividades_a_evaluar.actividad_evaluador = actividades.actividad
			AND actividades_a_evaluar.actividad_evaluado = act2.actividad
			AND actividades_a_evaluar.ubicacion_evaluador = ubicaciones.ubicacion
			AND actividades_a_evaluar.ubicacion_evaluado = ubicaciones2.ubicacion
			AND $where
		ORDER BY act_evaluador, act_evaluado
        ";
	return toba::db()->consultar($sql);
    }

    function get_ambitos_a_evaluar($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT 	*, 
			actividades.descripcion as actividad_evaluador_desc, 
			ambitos.descripcion as ambito_evaluado_desc,
			ubicaciones.codigo as ubicacion_evaluador,
			ubicaciones2.codigo as ubicacion_evaluado
		FROM 	ambitos_a_evaluar, 
			actividades, 
			ambitos, 
			ubicaciones, 
			ubicaciones as ubicaciones2
		WHERE ambitos_a_evaluar.actividad_evaluador = actividades.actividad
			AND ambitos_a_evaluar.ambito_evaluado = ambitos.ambito
			AND ambitos_a_evaluar.ubicacion_evaluador = ubicaciones.ubicacion
			AND ambitos_a_evaluar.ubicacion_evaluado = ubicaciones2.ubicacion
			AND $where
		ORDER BY actividad_evaluador_desc, ambito_evaluado_desc
        ";
	return toba::db()->consultar($sql);
    }

    function get_espacios_disciplinares($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM espacios_disciplinares
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_caracteres($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM caracteres
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_caracteres_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM caracteres
		WHERE activo = 'S' AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_dedicaciones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM dedicaciones
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_dedicaciones_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM dedicaciones
		WHERE activo = 'S' AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_dedicaciones_licencias_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM dedicaciones
		WHERE activo = 'S' AND licencia = 'S'
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_categorias_roles($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM categorias
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_categorias_roles_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM categorias
		WHERE activo = 'S' AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_categorias($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM categorias
		WHERE   es_categoria = 'S'
			AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_categorias_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM categorias
		WHERE 	activo = 'S' 
			AND es_categoria = 'S'
			AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_roles($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM categorias
		WHERE 	es_rol = 'S'
			AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_roles_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM categorias
		WHERE 	activo = 'S'
			AND es_rol = 'S'
			AND $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_dimensiones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM dimensiones
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_ambitos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM ambitos
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_cargos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT cargos.*, categorias.descripcion as categoria_desc,
			dedicaciones.descripcion as dedicacion_desc
		FROM cargos, categorias, dedicaciones
		WHERE 	cargos.categoria = categorias.categoria
			AND cargos.dedicacion = dedicaciones.dedicacion 
			AND $where
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_departamentos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM departamentos
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_estados($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM estados
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_estados_evaluaciones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM estados
		WHERE $where AND en_evaluacion = 'S'
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_designaciones_tipos_renuncia($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM designaciones_tipos
		WHERE designacion_tipo in (3,4,5,6,7,10) 
		AND $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_estados_designacion($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM estados
		WHERE $where
		AND en_designacion = 'S'
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_estados_asignacion($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM estados
		WHERE $where
		AND en_asignacion = 'S'
        ";
	return toba::db()->consultar($sql);
    }
    
    
    function get_designaciones_tipos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM designaciones_tipos
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_designaciones_tipos_licencias($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM designaciones_tipos
		WHERE designacion_tipo in (2,9) AND $where
        ";
	return toba::db()->consultar($sql);
    }    

    function get_licencias_tipos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM licencias_tipos
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_resoluciones_tipos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM resoluciones_tipos
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    
    function get_perfiles($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM perfiles
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_perfiles_estados($where=null)
    {
	if (!isset($where)) $where = '1=1';

        $sql = "SELECT *
		FROM perfiles_estados
		WHERE $where 
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }


    function get_perfiles_estados_activos($where=null)
    {
	if (!isset($where)) $where = '1=1';

        $sql = "SELECT *
		FROM perfiles_estados
		WHERE $where AND activo = 'S'
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_tipos_doc($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM tipos_doc
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_instituciones($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *, (SELECT substring(descripcion from 1 for 50)) as nombre_corto
		FROM instituciones
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_titulos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *, (SELECT substring(descripcion from 1 for 80)) as nombre_corto
		FROM titulos
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_titulos_por_alcance($alcance)
    {
	$where = " alcance = '".$alcance."'";
        $sql = "SELECT *, (SELECT substring(descripcion from 1 for 80)) as nombre_corto
		FROM titulos
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_excepciones_evaluaciones()
    {
	$sql = "SELECT 	personas.apellido || ', ' || personas.nombres as nombre_completo,
			CASE WHEN ee.parametro = 'PAR_AUTOEVAL_VENCE' THEN 'AUTOEVALUACION'
			ELSE 'EVALUACION'
			END as parametro_desc,
			desde,
			hasta,
			CASE WHEN habilitado = 'S' THEN 'Habilitado'
			ELSE 'Bloqueado'
			END as permiso,
			excepcion_evaluacion
		FROM excepciones_evaluaciones as ee LEFT OUTER JOIN personas ON (ee.persona = personas.persona)
			LEFT OUTER JOIN parametros ON (ee.parametro = parametros.parametro)
		";
	return toba::db()->consultar($sql);
    }

    function get_excepciones_persona($persona,$hoy,$parametro)
    {
	$sql = "SELECT *
		FROM excepciones_evaluaciones
		WHERE persona = $persona 
			AND '$hoy'::date between desde::date and hasta::date
			AND parametro = '$parametro'
		ORDER BY excepcion_evaluacion desc
		LIMIT 1
		";
	return toba::db()->consultar_fila($sql);
    }
}
?>
