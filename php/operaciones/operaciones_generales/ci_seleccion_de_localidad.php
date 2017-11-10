<?php
class ci_seleccion_de_localidad extends planta_ci
{
	protected $s__filtro;
	protected $s__datos;
	protected $s__incluir_cod_postal = false;

	function set_inclusion_cod_postal($incluir)
	{
		$this->s__incluir_cod_postal = $incluir;
	}
	
	function get_datos($filtro)
	{
		return toba::consulta_php('co_operaciones_generales')->get_listado_localidades($this->s__incluir_cod_postal, $filtro);
	}
	
	//---- cuadro de Localidades ---------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if (isset($this->s__filtro)) {
			$datos = $this->get_datos($this->s__filtro);
			$cuadro->set_datos($datos);
		}
	}

	//---- formulario de localidades ----------------------------------------------------

	function conf__form(toba_ei_formulario $form)
	{
		if (isset($this->s__datos)) {
			$form->set_datos($this->s__datos);
		}        
	}
	
	function evt__form__filtrar($datos)
	{
		if (isset($datos)) {
			$this->s__datos = $datos;
			$filtro = "";
			if (isset($datos['pais'])) {
				$filtro .= " mug_paises.pais = {$datos['pais']}"; 
			}
			if (isset($datos['provincia'])) {
				$filtro .= " AND mug_provincias.provincia = {$datos['provincia']}";
			}    
			if (isset($datos['localidad'])) {
				$filtro .= "AND mug_localidades.nombre ILIKE '%{$datos['localidad']}%'"; 
			}    
			$this->s__filtro = $filtro;
		} else {
			unset($this->s__datos);
			$this->s__filtro = "";
		}
	}
	
	function evt__form__cancelar()
	{
		unset($this->s__datos);
		unset($this->s__filtro);
	}
}
?>