<?php
class ci_cambiar_clave extends planta_ci
{
	protected $s__id;
	protected $s__clave;
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__procesar()
	{
		$idUsuario = $this->s__id;
		$claveUsuario = $this->s__clave;
			
		$clave_enc = encriptar_con_sal($claveUsuario, 'sha256');
		toba::consulta_php('co_toba_usuarios')->actualizar_clave($idUsuario, $clave_enc);
		toba::consulta_php('co_personas')->actualizar_clave($idUsuario, $clave_enc);      
		
		$this->informar_msg("La clave se modificó correctamente","info");
		toba::vinculador()->navegar_a('planta','280000216');
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
		$datos['nombre'] = toba::usuario()->get_nombre();
		$datos['usuario'] = toba::usuario()->get_id();
		$form->set_datos($datos);
	}

	function evt__form__modificacion($datos)
	{
		$this->s__id = toba::usuario()->get_id();
		$this->s__clave = $datos['clave'];
	}    
}
?>