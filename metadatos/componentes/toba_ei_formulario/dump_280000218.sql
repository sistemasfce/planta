------------------------------------------------------------
--[280000218]--  Sistema de evaluaciones - form 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'planta', --proyecto
	'280000218', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_ei_formulario', --clase
	'280000002', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'Sistema de evaluaciones - form', --nombre
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
	'2017-06-12 08:51:27', --creacion
	'abajo'  --posicion_botonera
);
--- FIN Grupo de desarrollo 280

------------------------------------------------------------
-- apex_objeto_eventos
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'planta', --proyecto
	'280000279', --evento_id
	'280000218', --objeto
	'cambiar_clave', --identificador
	'Cambiar clave', --etiqueta
	'1', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	'ei-boton-evaluaciones', --estilo
	'apex', --imagen_recurso_origen
	'password.png', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'1', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'planta', --proyecto
	'280000506', --evento_id
	'280000218', --objeto
	'historicos', --identificador
	'Ver años anteriores', --etiqueta
	'1', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	'ei-boton-evaluaciones', --estilo
	'apex', --imagen_recurso_origen
	'doc.gif', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'2', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'planta', --proyecto
	'280000199', --evento_id
	'280000218', --objeto
	'autoevaluacion', --identificador
	'Informe anual docente (autoevaluación)', --etiqueta
	'1', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	'ei-boton-evaluaciones', --estilo
	'apex', --imagen_recurso_origen
	'consulta_php.gif', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'3', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'planta', --proyecto
	'280000200', --evento_id
	'280000218', --objeto
	'desempenio', --identificador
	'Informe anual de desempeño (evaluación)', --etiqueta
	'1', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	'ei-boton-evaluaciones', --estilo
	'apex', --imagen_recurso_origen
	'contacto.png', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'4', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
--- FIN Grupo de desarrollo 280

------------------------------------------------------------
-- apex_objeto_ut_formulario
------------------------------------------------------------
INSERT INTO apex_objeto_ut_formulario (objeto_ut_formulario_proyecto, objeto_ut_formulario, tabla, titulo, ev_agregar, ev_agregar_etiq, ev_mod_modificar, ev_mod_modificar_etiq, ev_mod_eliminar, ev_mod_eliminar_etiq, ev_mod_limpiar, ev_mod_limpiar_etiq, ev_mod_clave, clase_proyecto, clase, auto_reset, ancho, ancho_etiqueta, expandir_descripcion, campo_bl, scroll, filas, filas_agregar, filas_agregar_online, filas_agregar_abajo, filas_agregar_texto, filas_borrar_en_linea, filas_undo, filas_ordenar, filas_ordenar_en_linea, columna_orden, filas_numerar, ev_seleccion, alto, analisis_cambios, no_imprimir_efs_sin_estado, resaltar_efs_con_estado, template, template_impresion) VALUES (
	'planta', --objeto_ut_formulario_proyecto
	'280000218', --objeto_ut_formulario
	NULL, --tabla
	NULL, --titulo
	NULL, --ev_agregar
	NULL, --ev_agregar_etiq
	NULL, --ev_mod_modificar
	NULL, --ev_mod_modificar_etiq
	NULL, --ev_mod_eliminar
	NULL, --ev_mod_eliminar_etiq
	NULL, --ev_mod_limpiar
	NULL, --ev_mod_limpiar_etiq
	NULL, --ev_mod_clave
	NULL, --clase_proyecto
	NULL, --clase
	NULL, --auto_reset
	'100%', --ancho
	'150px', --ancho_etiqueta
	'0', --expandir_descripcion
	NULL, --campo_bl
	NULL, --scroll
	NULL, --filas
	NULL, --filas_agregar
	'1', --filas_agregar_online
	'0', --filas_agregar_abajo
	NULL, --filas_agregar_texto
	'0', --filas_borrar_en_linea
	NULL, --filas_undo
	NULL, --filas_ordenar
	'0', --filas_ordenar_en_linea
	NULL, --columna_orden
	NULL, --filas_numerar
	NULL, --ev_seleccion
	NULL, --alto
	NULL, --analisis_cambios
	'0', --no_imprimir_efs_sin_estado
	'0', --resaltar_efs_con_estado
	'<table>
	<tbody>
		<tr>
			<td>
				<strong><span style="font-size:14px;">Bienvenido/a</span></strong></td>
			<td>
				<span style="color:#0000cd;"><em><span style="font-size:16px;"><strong>[ef id=nombre]</strong></span></em></span></td>
			<td>
				<strong><span style="font-size: 14px;">al sistema de evaluaci&oacute;n de actividades</span></strong></td>
		</tr>
	</tbody>
</table>
<p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;">El sistema generar&aacute; dos informes: el <b>INFORME ANUAL DOCENTE</b> <strong>(Autoevaluaci&oacute;n)</strong> y el <b>INFORME ANUAL DE DESEMPE&Ntilde;O</b> <strong>(Evaluaci&oacute;n)</strong>, de acuerdo a lo requerido por la Ordenanza Nro. 145/12 CS y la Disposici&oacute;n N&deg; 01/16 CDFCE como instrumentos de evaluaci&oacute;n en el R&eacute;gimen de Carrera Acad&eacute;mica.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;">El <b>INFORME ANUAL DOCENTE</b> es el resultante de un proceso de autoevaluaci&oacute;n que cada docente realizara sobre las actividades que le fueran asignadas y/o realizadas en las dimensiones de la Carrera Acad&eacute;mica, junto a la actualizaci&oacute;n anual del Curr&iacute;culum Vitae Acad&eacute;mico.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;"><font style="font-size: 9pt"><i><b>La responsabilidad por la autoevaluaci&oacute;n es de cada persona, por lo tanto, quienes no realicen esta etapa del proceso ser&aacute;n evaluados en forma negativa, de acuerdo a lo establecido en el Cap&iacute;tulo 7, inciso 7.12, de la Ordenanza N&deg; 145/12 CS R&eacute;gimen de Carrera Acad&eacute;mica.</b></i></font></span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm; border: 1px solid #00000a; padding: 0.04cm 0.14cm"><span style="font-size:12px;">LA CONFIRMACI&Oacute;N EN EL SISTEMA DE ESTE INFORME VENCE EL D&Iacute;A 11 DEL MES DE MAYO DEL A&Ntilde;O 2022.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><br />
<span style="font-size:12px;">El <b>INFORME ANUAL DE DESEMPE&Ntilde;O</b> es el resultante de un proceso de evaluaci&oacute;n en el que los docentes pueden asumir dos roles: evaluador y evaluado.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;">El <strong>evaluador</strong> realizar&aacute; la evaluaci&oacute;n de las actividades asignadas durante el ciclo lectivo de los docentes a su cargo. Para ello, se le presentar&aacute; una pantalla con el listado de los docentes y de las actividades a evaluar.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;">El <strong>evaluado</strong> recibir&aacute; la evaluaci&oacute;n que el docente responsable realiz&oacute; y un plan de mejora, en caso de corresponder. El evaluado deber&aacute; notificarse de las evaluaciones recibidas.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;"><font style="font-size: 9pt"><i><b>Es obligaci&oacute;n del docente responsable evaluar a los docentes a su cargo, tal como lo establece el Cap&iacute;tulo 7, inciso 7.10, de la Ordenanza N&deg; 145/12 CS R&eacute;gimen de Carrera Acad&eacute;mica. Si no lo hiciera, ser&aacute; evaluado en forma negativa, de acuerdo a lo establecido en el Cap&iacute;tulo 7, inciso 7.12, de la Ordenanza N&deg; 145/12 CS R&eacute;gimen de Carrera Acad&eacute;mica.</b></i></font></span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm; border: 1px solid #00000a; padding: 0.04cm 0.14cm"><span style="font-size:12px;">LA CONFIRMACI&Oacute;N EN EL SISTEMA DE ESTE INFORME VENCE EL DIA 12 DEL MES DE JUNIO DEL A&Ntilde;O 2022.</span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;">Se les recuerda que el sistema de Evaluaci&oacute;n Docente es un proceso sucesivo que deber&aacute; iniciarse por el <b>INFORME ANUAL DOCENTE,</b> por lo que si no finaliza esta etapa completa (Curr&iacute;culum Vitae Acad&eacute;mico y Actividades), no podr&aacute; avanzar en la carga de la informaci&oacute;n que conformar&aacute; el <b>INFORME ANUAL DE DESEMPE&Ntilde;O. </b></span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm"><span style="font-size:12px;">Manual de sistema de evaluaci&oacute;n docente: <a href="http://web.sistemasfce.com.ar/wordpress/wp-content/uploads/2020/02/Disp._002-17_D_Manual_SisEvalActiv.pdf" target="_blank">descargar</a></span></p><p align="JUSTIFY" style="margin-bottom: 0.35cm; border: 1px solid #00000a; padding: 0.04cm 0.14cm"><span style="font-size:12px;"><strong>CONSULTAS:</strong></span><br />
<br />
<span style="font-size:12px;">En el caso de <strong>consultas, dudas, errores u omisiones sobre las actividades docentes</strong>, comunicarse con el siguiente correo electronico: <a href="mailto:evaluaciondocente@economicasunp.edu.ar">evaluaciondocente@economicasunp.edu.ar</a><br />
<br />
Para <strong>consultas t&eacute;cnicas</strong> sobre el sistema comunicarse al correo electr&oacute;nico: <a href="mailto:sistemas@economicasunp.edu.ar">sistemas@economicasunp.edu.ar</a></span></p>
<div>
	&nbsp;</div>', --template
	NULL  --template_impresion
);

------------------------------------------------------------
-- apex_objeto_ei_formulario_ef
------------------------------------------------------------

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_objeto_ei_formulario_ef (objeto_ei_formulario_fila, objeto_ei_formulario, objeto_ei_formulario_proyecto, identificador, elemento_formulario, columnas, obligatorio, oculto_relaja_obligatorio, orden, etiqueta, etiqueta_estilo, descripcion, colapsado, desactivado, estilo, total, inicializacion, permitir_html, deshabilitar_rest_func, estado_defecto, solo_lectura, solo_lectura_modificacion, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_maestros, carga_cascada_relaj, cascada_mantiene_estado, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_filas, edit_columnas, edit_wrap, edit_resaltar, edit_ajustable, edit_confirmar_clave, edit_expreg, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, fieldset_fin, check_valor_si, check_valor_no, check_desc_si, check_desc_no, check_ml_toggle, fijo_sin_estado, editor_ancho, editor_alto, editor_botonera, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, upload_extensiones, punto_montaje, placeholder) VALUES (
	'280000327', --objeto_ei_formulario_fila
	'280000218', --objeto_ei_formulario
	'planta', --objeto_ei_formulario_proyecto
	'nombre', --identificador
	'ef_fijo', --elemento_formulario
	'nombre', --columnas
	'0', --obligatorio
	'0', --oculto_relaja_obligatorio
	'1', --orden
	NULL, --etiqueta
	NULL, --etiqueta_estilo
	NULL, --descripcion
	NULL, --colapsado
	NULL, --desactivado
	NULL, --estilo
	NULL, --total
	NULL, --inicializacion
	NULL, --permitir_html
	NULL, --deshabilitar_rest_func
	NULL, --estado_defecto
	NULL, --solo_lectura
	'0', --solo_lectura_modificacion
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
	NULL, --carga_maestros
	NULL, --carga_cascada_relaj
	'0', --cascada_mantiene_estado
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_filas
	NULL, --edit_columnas
	NULL, --edit_wrap
	NULL, --edit_resaltar
	NULL, --edit_ajustable
	NULL, --edit_confirmar_clave
	NULL, --edit_expreg
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --fieldset_fin
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --check_ml_toggle
	NULL, --fijo_sin_estado
	NULL, --editor_ancho
	NULL, --editor_alto
	NULL, --editor_botonera
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL, --upload_extensiones
	NULL, --punto_montaje
	NULL  --placeholder
);
--- FIN Grupo de desarrollo 280
