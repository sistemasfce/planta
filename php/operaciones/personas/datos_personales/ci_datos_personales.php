<?php
class ci_datos_personales extends planta_ci
{
	protected $s__claveAnterior;
	protected $s__modifica;
	
	//-------------------------------------------------------------------------
	function relacion()
	{
		return $this->controlador->dep('relacion');
	}
	
	//-------------------------------------------------------------------------
	function tabla($id)
	{
		return $this->controlador->dep('relacion')->tabla($id);    
	}

	//-------------------------------------------------------------------------
	function relacionToba()
	{
		return $this->controlador->dep('relacion_toba');
	}
	
	//-------------------------------------------------------------------------
	function tablaToba($id)
	{
		return $this->controlador->dep('relacion_toba')->tabla($id);    
	}    
	
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		$datos = toba::consulta_php('co_personas')->get_personas($where);
		$datos_aux = array();
		
		foreach ($datos as $fila) {
			if ($fila['estado_docente'] == 1 ) {
				$fila['estado_docente_desc'] = '<font color=green><b>'.$fila['estado_docente_desc'].'</b></font>';
			} else {
				$fila['estado_docente_desc'] = '<font color=red><b>'.$fila['estado_docente_desc'].'</b></font>';
			}
			if ($fila['estado_nodocente'] == 1 ) {
				$fila['estado_nodocente_desc'] = '<font color=green><b>'.$fila['estado_nodocente_desc'].'</b></font>';
			} else {
				$fila['estado_nodocente_desc'] = '<font color=red><b>'.$fila['estado_nodocente_desc'].'</b></font>';
			}
			if ($fila['estado_externo'] == 1 ) {
				$fila['estado_externo_desc'] = '<font color=green><b>'.$fila['estado_externo_desc'].'</b></font>';
			} else {
				$fila['estado_externo_desc'] = '<font color=red><b>'.$fila['estado_externo_desc'].'</b></font>';
			}
			$datos_aux[] = $fila; 
		}
		$cuadro->set_datos($datos_aux);
	}
	
	function evt__cuadro__seleccion($seleccion)
	{
		$this->relacion()->cargar($seleccion);
		$this->set_pantalla('edicion');
	}
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('edicion');
	}

	function evt__cancelar()
	{
		$this->dep('relacion')->resetear();
		$this->set_pantalla('seleccion');
	}

	function evt__eliminar()
	{
		try {
			$this->dep('relacion')->eliminar_todo();
			$this->set_pantalla('seleccion');
		} catch (toba_error $e) {
			toba::notificacion()->agregar('No es posible eliminar el registro.');
		}
	}

	function evt__guardar()
	{
		if ($this->s__modifica) {
			$this->dep('relacion')->sincronizar();
			$this->dep('relacion')->resetear();
		} else {
			try {
				$this->dep('relacion')->sincronizar();
				$this->dep('relacion')->resetear();
				$this->dep('relacion_toba')->sincronizar();
				$this->dep('relacion_toba')->resetear();
			} catch (toba_error $e) {
				$this->informar_msg('Error al dar de alta usuario - '. $e->get_mensaje());
				return;
			}  
		}    
		
		$this->set_pantalla('seleccion');
	}

	//-----------------------------------------------------------------------------------
	//---- form_ml ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml(planta_ei_formulario_ml $form_ml)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('personas_titulos')->get_filas();
			$form_ml->set_datos($datos);
		}
	}

	function evt__form_ml__modificacion($datos)
	{
		$this->tabla('personas_titulos')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_ml ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml_inv(planta_ei_formulario_ml $form_ml)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('personas_categoria_inv')->get_filas();
			$form_ml->set_datos($datos);
		}
	}

	function evt__form_ml_inv__modificacion($datos)
	{
		$this->tabla('personas_categoria_inv')->procesar_filas($datos);
	}
        
	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('personas')->get();
			if ($datos['estado_docente'] == 1)
				$datos['perfil_docente'] = 'S';
			else
				$datos['perfil_docente'] = 'N';
			if ($datos['estado_nodocente'] == 1)
				$datos['perfil_no_docente'] = 'S';
			else
				$datos['perfil_no_docente'] = 'N';
			if ($datos['estado_externo'] == 1)
				$datos['perfil_externo'] = 'S';
			else
				$datos['perfil_externo'] = 'N';    
	
			$this->s__claveAnterior = $datos['clave'];
			$form->set_datos($datos);
		}
	}

	function evt__form__modificacion($datos)
	{
		$claveUsuario = $datos['clave'];
		if ($this->s__claveAnterior != $claveUsuario) {
			$clave_enc = encriptar_con_sal($claveUsuario, 'sha256');
			$datos['clave'] = $clave_enc;
			// actualizar tambien la clave en tabla toba
			toba::consulta_php('co_toba_usuarios')->actualizar_clave($datos['documento'],$clave_enc);
		}
		
		$this->tabla('personas')->set($datos);
		$buscoUsuario = toba::consulta_php('co_toba_usuarios')->busca_usuario($datos['documento']);
		// si la persona es nueva, no esta en tabla toba usuarios
		if (!isset($buscoUsuario['usuario'])) {
			$datosUser['usuario'] = $datos['documento'];
			$datosUser['nombre'] = $datos['apellido'] . ' ' .$datos['nombres'];
			$datosUser['clave'] = $datos['clave'];
			$datosUser['email'] = $datos['email'];
			$datosUser['autentificacion'] = 'sha256';
			$datosUser['bloqueado'] = 0;
		
			$datosPro['proyecto'] = 'planta';
			$datosPro['usuario'] = $datos['documento'];
			$datosPro['usuario_grupo_acc'] = 'docente';
			$this->tablaToba('basica')->set($datosUser);
			$this->tablaToba('proyecto')->nueva_fila($datosPro);
			$this->s__modifica = false;
		} else {
			$this->s__modifica = true;
		}
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(planta_ei_filtro $filtro)
	{
		if (isset($this->s__filtro)) {
			$filtro->set_datos($this->s__filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

}
?>