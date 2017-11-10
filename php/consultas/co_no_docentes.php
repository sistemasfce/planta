<?php

class co_no_docentes
{
    function get_no_docentes($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT 	*,
			apellido || ', ' || nombres as nombre_completo
		FROM 	personas, personas_perfiles
		WHERE   personas.persona = personas_perfiles.persona
		        AND perfil = 2 -- no docentes
			AND 1 = (SELECT pp.perfil_estado FROM personas_perfiles as pp WHERE
						pp.persona = personas.persona 
						AND pp.perfil = 2 ORDER BY pp.fecha, pp.persona_perfil DESC LIMIT 1) 
			AND $where
		ORDER BY nombre_completo
        ";
	return toba::db()->consultar($sql);
    }
}

?>
