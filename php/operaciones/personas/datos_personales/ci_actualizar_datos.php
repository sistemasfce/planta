<?php
class ci_actualizar_datos extends planta_ci
{	
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
                    $datos_aux[] = $fila; 
                } 
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

	function evt__cancelar()
	{
		$this->dep('relacion')->resetear();
		$this->set_pantalla('seleccion');
	}

	function evt__guardar()
	{
            try {
                $this->dep('relacion')->sincronizar();
                $this->dep('relacion')->resetear();
            } catch (toba_error $e) {
                $this->informar_msg('Error al dar de alta usuario - '. $e->get_mensaje());
                return;
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
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
            if ($this->relacion()->esta_cargada()) {
                $cargo = '';
                $datos = $this->tabla('personas')->get();
                $cargo_actual = toba::consulta_php('co_designaciones')->get_cargo_actual($datos['persona']);
                foreach ($cargo_actual as $ca) {
                    $cargo .= $ca['cargo_actual'] . ' | ';
                }
                $datos['cargo_actual'] = $cargo;
                $form->set_datos($datos);
            }
	}

	function evt__form__modificacion($datos)
	{
		$this->tabla('personas')->set($datos);
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