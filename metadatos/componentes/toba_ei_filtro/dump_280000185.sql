------------------------------------------------------------
--[280000185]--  fceConsulta individual (activas) - filtro 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'planta', --proyecto
	'280000185', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_ei_filtro', --clase
	'280000002', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'fceConsulta individual (activas) - filtro', --nombre
	NULL, --titulo
	'0', --colapsable
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
	'2017-06-07 12:00:47', --creacion
	'abajo'  --posicion_botonera
);
--- FIN Grupo de desarrollo 280

------------------------------------------------------------
-- apex_objeto_eventos
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'planta', --proyecto
	'280000185', --evento_id
	'280000185', --objeto
	'filtrar', --identificador
	'&Filtrar', --etiqueta
	'1', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	'ei-boton-filtrar', --estilo
	'apex', --imagen_recurso_origen
	'filtrar.png', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'1', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	'cargado,no_cargado', --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	NULL, --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'planta', --proyecto
	'280000184', --evento_id
	'280000185', --objeto
	'cancelar', --identificador
	'&Limpiar', --etiqueta
	'0', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	'ei-boton-limpiar', --estilo
	'apex', --imagen_recurso_origen
	'limpiar.png', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'2', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	'cargado', --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	NULL, --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
--- FIN Grupo de desarrollo 280

------------------------------------------------------------
-- apex_objeto_ei_filtro
------------------------------------------------------------
INSERT INTO apex_objeto_ei_filtro (objeto_ei_filtro_proyecto, objeto_ei_filtro, ancho) VALUES (
	'planta', --objeto_ei_filtro_proyecto
	'280000185', --objeto_ei_filtro
	'100%'  --ancho
);

------------------------------------------------------------
-- apex_objeto_ei_filtro_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000043', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'opciones', --tipo
	'persona', --nombre
	'asignaciones.persona', --expresion
	'Persona', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'3', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	'ef_combo', --opciones_ef
	'get_docentes', --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	'280000004', --carga_consulta_php
	NULL, --carga_sql
	'planta', --carga_fuente
	NULL, --carga_lista
	'persona', --carga_col_clave
	'nombre_completo', --carga_col_desc
	'1', --carga_permite_no_seteado
	'-- Seleccione --', --carga_no_seteado
	'0', --carga_no_seteado_ocultar
	NULL, --carga_maestros
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	'280000002', --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000083', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'cadena', --tipo
	'documento', --nombre
	'personas.documento', --expresion
	'Documento', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'1', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	'8', --edit_tamano
	'8', --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000090', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'cadena', --tipo
	'apellido', --nombre
	'personas.apellido', --expresion
	'Apellido', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'2', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000143', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'fecha', --tipo
	'desde', --nombre
	'asignaciones.fecha_desde', --expresion
	'Desde', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'4', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000144', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'fecha', --tipo
	'hasta', --nombre
	'asignaciones.fecha_hasta', --expresion
	'Hasta', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'5', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000145', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'cadena', --tipo
	'resolucion', --nombre
	'asignaciones.resolucion', --expresion
	'N� Resol.', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'6', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	'10', --edit_tamano
	'10', --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000146', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'cadena', --tipo
	'resolucion_anio', --nombre
	'asignaciones.resolucion_anio', --expresion
	'A�o Resol.', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'7', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	'5', --edit_tamano
	'4', --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	'/^[0-9]{4}$/', --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000147', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'opciones', --tipo
	'resolucion_tipo', --nombre
	'asignaciones.resolucion_tipo', --expresion
	'Tipo Resol', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'8', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	'ef_combo', --opciones_ef
	'get_resoluciones_tipos', --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	'280000002', --carga_consulta_php
	NULL, --carga_sql
	'planta', --carga_fuente
	NULL, --carga_lista
	'resolucion_tipo', --carga_col_clave
	'descripcion', --carga_col_desc
	'1', --carga_permite_no_seteado
	'-- seleccione --', --carga_no_seteado
	'0', --carga_no_seteado_ocultar
	NULL, --carga_maestros
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	'280000002', --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000246', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'cadena', --tipo
	'ciclo_lectivo', --nombre
	'asignaciones.ciclo_lectivo', --expresion
	'Ciclo lectivo', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'9', --orden
	NULL, --estado_defecto
	'0', --opciones_es_multiple
	NULL, --opciones_ef
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	'5', --edit_tamano
	'4', --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL  --placeholder
);
INSERT INTO apex_objeto_ei_filtro_col (objeto_ei_filtro_col, objeto_ei_filtro, objeto_ei_filtro_proyecto, tipo, nombre, expresion, etiqueta, descripcion, obligatorio, inicial, orden, estado_defecto, opciones_es_multiple, opciones_ef, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, carga_maestros, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_expreg, estilo, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, punto_montaje, check_valor_si, check_valor_no, check_desc_si, check_desc_no, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, placeholder) VALUES (
	'280000327', --objeto_ei_filtro_col
	'280000185', --objeto_ei_filtro
	'planta', --objeto_ei_filtro_proyecto
	'opciones', --tipo
	'estado', --nombre
	'asignaciones.estado', --expresion
	'Estado', --etiqueta
	NULL, --descripcion
	'0', --obligatorio
	'1', --inicial
	'10', --orden
	NULL, --estado_defecto
	'1', --opciones_es_multiple
	'ef_multi_seleccion_check', --opciones_ef
	'get_estados', --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	'280000002', --carga_consulta_php
	NULL, --carga_sql
	'planta', --carga_fuente
	NULL, --carga_lista
	'estado', --carga_col_clave
	'descripcion', --carga_col_desc
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --carga_maestros
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_expreg
	NULL, --estilo
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	'280000002', --punto_montaje
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	'0', --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	'0', --selec_serializar
	'6', --selec_cant_columnas
	NULL  --placeholder
);
--- FIN Grupo de desarrollo 280
