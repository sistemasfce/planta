------------------------------------------------------------
--[280000090]--  DT - asignaciones 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'planta', --proyecto
	'280000090', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'280000002', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'DT - asignaciones', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'planta', --fuente_datos_proyecto
	'planta', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2017-05-18 12:39:05', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 280

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'280000002', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'asignaciones', --tabla
	NULL, --tabla_ext
	NULL, --alias
	'0', --modificar_claves
	'planta', --fuente_datos_proyecto
	'planta', --fuente_datos
	'1', --permite_actualizacion_automatica
	'negocio', --esquema
	'negocio'  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'812', --col_id
	'asignacion', --columna
	'E', --tipo
	'1', --pk
	'asignaciones_asignacion_seq', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'813', --col_id
	'designacion', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'814', --col_id
	'persona', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'815', --col_id
	'dimension', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'816', --col_id
	'actividad', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'817', --col_id
	'departamento', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'818', --col_id
	'rol', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'819', --col_id
	'carga_horaria', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'820', --col_id
	'ubicacion', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'821', --col_id
	'ciclo_lectivo', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'822', --col_id
	'fecha_desde', --columna
	'F', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'823', --col_id
	'fecha_hasta', --columna
	'F', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'824', --col_id
	'resolucion', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'5', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'825', --col_id
	'resolucion_fecha', --columna
	'F', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'826', --col_id
	'resolucion_anio', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'827', --col_id
	'resolucion_tipo', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'828', --col_id
	'responsable', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'829', --col_id
	'carrera_academica', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'830', --col_id
	'observaciones', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'500', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'831', --col_id
	'estado', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'832', --col_id
	'fecha_carga', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'833', --col_id
	'cambia_estado', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'834', --col_id
	'autoeval_informe_catedra', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'835', --col_id
	'autoeval_informe_catedra_path', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'500', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'836', --col_id
	'autoeval_programa', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'837', --col_id
	'autoeval_programa_path', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'500', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'838', --col_id
	'autoeval_tipo_informe', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'50', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'839', --col_id
	'autoeval_informe_otros', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'840', --col_id
	'autoeval_informe_otros_path', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'500', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'841', --col_id
	'autoeval_pregunta1', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'842', --col_id
	'autoeval_pregunta2', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'843', --col_id
	'autoeval_pregunta3', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'844', --col_id
	'autoeval_pregunta4', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'845', --col_id
	'autoeval_calificacion', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'25', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'846', --col_id
	'autoeval_observaciones', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'847', --col_id
	'autoeval_confirmado', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'848', --col_id
	'autoeval_estado', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'849', --col_id
	'eval_evaluador', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'850', --col_id
	'eval_calificacion', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'30', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'851', --col_id
	'eval_calificacion_fecha', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'852', --col_id
	'eval_plan_de_mejora', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'853', --col_id
	'eval_desvio1', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'854', --col_id
	'eval_desvio2', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'855', --col_id
	'eval_desvio3', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'856', --col_id
	'eval_act1', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'857', --col_id
	'eval_act2', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'858', --col_id
	'eval_act3', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'859', --col_id
	'eval_desempenio1', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'860', --col_id
	'eval_desempenio2', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'861', --col_id
	'eval_desempenio3', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'862', --col_id
	'eval_observaciones', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'863', --col_id
	'eval_notificacion', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'864', --col_id
	'eval_notificacion_fecha', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'865', --col_id
	'eval_notificacion_observaciones', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'2000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'866', --col_id
	'eval_confirmado', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'867', --col_id
	'eval_estado', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'868', --col_id
	'fecha_cambios', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'869', --col_id
	'autoeval_confirmado_fecha', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'870', --col_id
	'eval_confirmado_fecha', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'871', --col_id
	'autoeval_calificacion_fecha', --columna
	'T', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'872', --col_id
	'autoeval_pregunta1_pandemia', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'planta', --objeto_proyecto
	'280000090', --objeto
	'873', --col_id
	'autoeval_pregunta2_pandemia', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'4000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'asignaciones'  --tabla
);
--- FIN Grupo de desarrollo 0
