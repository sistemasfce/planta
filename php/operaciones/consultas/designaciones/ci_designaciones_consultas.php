<?php
class ci_designaciones_consultas extends planta_ci
{
	protected $s__filtro;
	
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
	//---- cuadro_des ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_des(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		if ($where != '1=1') {
			$datos = toba::consulta_php('co_designaciones')->get_designaciones($where);
			$datos_para_cuadro = array();
			foreach ($datos as $dat) {
				$fila = $dat;
				if ($fila['designacion_tipo'] == 1) {
					$horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
					$fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
				}

				if ($dat['estado'] == 1 or $dat['estado'] == 6) {
					$fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
				}
				if ($dat['estado'] == 3 ) {
					$fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
				}  else {
					$fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
				}
				$datos_para_cuadro[] = $fila;
			}
			$cuadro->set_datos($datos_para_cuadro);
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
                $filtro->columna('desde')->set_condicion_fija('entre');
                $filtro->columna('hasta')->set_condicion_fija('entre');
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