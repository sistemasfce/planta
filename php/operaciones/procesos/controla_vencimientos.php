<?php
	//-------------------------------------------------VENCIMIENTOS DE LICENCIAS DESIGNACIONES ----------------------------
	// busco las designaciones con licencia vencida
	$sql = "SELECT *
		FROM designaciones
		WHERE designacion_tipo in (2,9) 
		AND estado = 6
		AND fecha_hasta::date < current_date
		AND designacion_padre is not null
		";
	$datos = toba::db()->consultar($sql);

	// vuelvo a poner como activas las designaciones que tenian licencia vencida y estaban con estado en_licencia
	foreach ($datos as $dat) {
		
		$sql = "UPDATE designaciones
			SET estado = 1
			WHERE designacion = ".$dat['designacion_padre'];
		toba::db()->consultar($sql);

		// activar las asignaciones del docente que estaban con licencia
		$sql_asig = "UPDATE asignaciones
		SET estado = 1
		WHERE designacion = ".$dat['designacion_padre']." AND estado = 7 ";
		toba::db()->consultar($sql_asig);
                
                // paso a finalizado la licencia que estaba activa
                $sql_lice = "UPDATE designaciones
                SET estado = 15
                WHERE designacion = ".$dat['designacion_padre']." AND estado = 6 ";
                toba::db()->consultar($sql_lice);
	}

	//----------------------------------------------VENCIMIENTOS DE ACTIVOS designaciones/asignaciones-----------------

	// pongo como finalizado las designaciones que eran activas y finalizaron	
	$sql = "UPDATE designaciones
		SET estado = 15, fecha_cambios = now()
		WHERE estado = 1 AND fecha_hasta::date < current_date 
		";
	toba::db()->consultar($sql);

	// pongo como finalizado las asignaciones activas que finalizaron
	$sql = "UPDATE asignaciones
		SET estado = 15, cambia_estado = 'N', fecha_cambios = now()
		WHERE estado = 1 AND fecha_hasta::date < current_date
		";
	toba::db()->consultar($sql);

	//---------------------------------------------- ACTIVO LAS PENDIENTES  -------------------------------------------

	// pongo como activas las designaciones que eran pendientes y se activaron
	$sql = "UPDATE designaciones
		SET estado = 1
		WHERE estado = 2 AND fecha_desde::date <= current_date
		";
	toba::db()->consultar($sql);

?>
