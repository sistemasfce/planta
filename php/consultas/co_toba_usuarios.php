<?php

class co_toba_usuarios
{
    function busca_usuario($documento)
    {
        $sql = "SELECT 	usuario
		FROM 	desarrollo.apex_usuario
		WHERE   usuario = '$documento'
        ";
	return toba::db('toba_usuarios')->consultar_fila($sql);
    }
    function actualizar_clave($documento,$clave)
    {
        $sql = "UPDATE desarrollo.apex_usuario
		SET clave = '$clave'
		WHERE usuario = '$documento'
        ";
	return toba::db('toba_usuarios')->consultar($sql);
    }
}

?>
