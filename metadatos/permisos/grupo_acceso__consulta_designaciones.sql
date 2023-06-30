
------------------------------------------------------------
-- apex_usuario_grupo_acc
------------------------------------------------------------
INSERT INTO apex_usuario_grupo_acc (proyecto, usuario_grupo_acc, nombre, nivel_acceso, descripcion, vencimiento, dias, hora_entrada, hora_salida, listar, permite_edicion, menu_usuario) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	'consulta_designaciones', --nombre
	NULL, --nivel_acceso
	'Permite consultar designaciones', --descripcion
	NULL, --vencimiento
	NULL, --dias
	NULL, --hora_entrada
	NULL, --hora_salida
	NULL, --listar
	'1', --permite_edicion
	NULL  --menu_usuario
);

------------------------------------------------------------
-- apex_usuario_grupo_acc_item
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'1'  --item
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 280
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000010'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000040'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000042'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000043'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000044'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000052'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000112'  --item
);
INSERT INTO apex_usuario_grupo_acc_item (proyecto, usuario_grupo_acc, item_id, item) VALUES (
	'planta', --proyecto
	'consulta_designaciones', --usuario_grupo_acc
	NULL, --item_id
	'280000149'  --item
);
--- FIN Grupo de desarrollo 280
