
------------------------------------------------------------
-- apex_usuario_grupo_acc
------------------------------------------------------------
INSERT INTO apex_usuario_grupo_acc (proyecto, usuario_grupo_acc, nombre, nivel_acceso, descripcion, vencimiento, dias, hora_entrada, hora_salida, listar, permite_edicion, menu_usuario) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	'docente', --nombre
	NULL, --nivel_acceso
	'Usuario para docentes y externos', --descripcion
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
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'1'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'2'  --item
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000010'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000041'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000065'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000077'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000078'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000079'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'docente', --usuario_grupo_acc
	NULL, --item_id
	'280000166'  --item
);
--- FIN Grupo de desarrollo 280
