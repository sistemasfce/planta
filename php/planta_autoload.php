<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class planta_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'planta_ci' => 'extension_toba/componentes/planta_ci.php',
		'planta_cn' => 'extension_toba/componentes/planta_cn.php',
		'planta_datos_relacion' => 'extension_toba/componentes/planta_datos_relacion.php',
		'planta_datos_tabla' => 'extension_toba/componentes/planta_datos_tabla.php',
		'planta_ei_arbol' => 'extension_toba/componentes/planta_ei_arbol.php',
		'planta_ei_archivos' => 'extension_toba/componentes/planta_ei_archivos.php',
		'planta_ei_calendario' => 'extension_toba/componentes/planta_ei_calendario.php',
		'planta_ei_codigo' => 'extension_toba/componentes/planta_ei_codigo.php',
		'planta_ei_cuadro' => 'extension_toba/componentes/planta_ei_cuadro.php',
		'planta_ei_esquema' => 'extension_toba/componentes/planta_ei_esquema.php',
		'planta_ei_filtro' => 'extension_toba/componentes/planta_ei_filtro.php',
		'planta_ei_firma' => 'extension_toba/componentes/planta_ei_firma.php',
		'planta_ei_formulario' => 'extension_toba/componentes/planta_ei_formulario.php',
		'planta_ei_formulario_ml' => 'extension_toba/componentes/planta_ei_formulario_ml.php',
		'planta_ei_grafico' => 'extension_toba/componentes/planta_ei_grafico.php',
		'planta_ei_mapa' => 'extension_toba/componentes/planta_ei_mapa.php',
		'planta_servicio_web' => 'extension_toba/componentes/planta_servicio_web.php',
		'planta_comando' => 'extension_toba/planta_comando.php',
		'planta_modelo' => 'extension_toba/planta_modelo.php',
	);
}
?>