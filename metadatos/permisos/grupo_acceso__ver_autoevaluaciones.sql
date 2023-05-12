
------------------------------------------------------------
-- apex_usuario_grupo_acc
------------------------------------------------------------
INSERT INTO apex_usuario_grupo_acc (proyecto, usuario_grupo_acc, nombre, nivel_acceso, descripcion, vencimiento, dias, hora_entrada, hora_salida, listar, permite_edicion, menu_usuario) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	'ver_autoevaluaciones', --nombre
	NULL, --nivel_acceso
	'Puede ver autoevaluaciones', --descripcion
	NULL, --vencimiento
	NULL, --dias
	NULL, --hora_entrada
	NULL, --hora_salida
	NULL, --listar
	'0', --permite_edicion
	NULL  --menu_usuario
);

------------------------------------------------------------
-- apex_usuario_grupo_acc_item
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'1'  --item
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000038'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000053'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000054'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000077'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000080'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'ver_autoevaluaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000144'  --item
);
--- FIN Grupo de desarrollo 280
