<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

    //-------------------------------------------------VENCIMIENTOS DE LICENCIAS DESIGNACIONES ----------------------------
    // busco las designaciones tipo licencia que esten vencidas
    $tipo_lic = comunes::desig_licencia;
    $vigente = comunes::estado_vigente;
    $activo = comunes::estado_activo;
    $historico = comunes::estado_historico;
    $con_licencia = comunes::estado_con_licencia;
    $finalizada = comunes::estado_finalizada;
    $sql = "
        SELECT *
        FROM designaciones 
        WHERE designacion_tipo in ($tipo_lic)
            AND estado = $vigente
            AND fecha_hasta::date < current_date
            AND designacion in (SELECT designacion_nueva FROM designaciones_modificadas)
            ";
    $datos = toba::db()->consultar($sql);

    // vuelvo a poner como activas las designaciones que tenian licencia vencida y estaban con estado historico
    foreach ($datos as $dat) {
            $sql = "UPDATE designaciones
                    SET estado = $activo
                    FROM designaciones_modificadas
                    WHERE designacion = designaciones_modificadas.designacion_anterior 
                    AND designaciones_modificadas.designacion_nueva = ".$dat['designacion'];
            toba::db()->consultar($sql);

            // activar las asignaciones del docente que estaban con licencia
            $sql_asig = "UPDATE asignaciones
            SET estado = $activo
            WHERE asignaciones.designacion = designaciones_modificadas.designacion_anterior 
                    AND designaciones_modificadas.designacion_nueva = ".$dat['designacion'].
                    " AND asignaciones.estado = $con_licencia ";
            toba::db()->consultar($sql_asig);

            // paso a historico la licencia que estaba activa
            $sql_lice = "UPDATE designaciones
            SET estado = $historico
            WHERE designacion = ".$dat['designacion']." AND estado = $vigente ";
            toba::db()->consultar($sql_lice);
    }

    //----------------------------------------------VENCIMIENTOS DE ACTIVOS designaciones/asignaciones-----------------

    // pongo como finalizado las designaciones que eran activas y finalizaron	
    $sql = "UPDATE designaciones
            SET estado = $finalizada, fecha_cambios = now()
            WHERE estado = $activo AND fecha_hasta::date < current_date 
            ";
    toba::db()->consultar($sql);

    // pongo como finalizado las asignaciones activas que finalizaron
    $sql = "UPDATE asignaciones
            SET estado = $finalizada, cambia_estado = 'N', fecha_cambios = now()
            WHERE estado = $activo AND fecha_hasta::date < current_date
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
