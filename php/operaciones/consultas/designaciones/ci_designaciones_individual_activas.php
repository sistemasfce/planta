<?php
class ci_designaciones_individual_activas extends planta_ci
{
	protected $s__filtro;
	
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_des(planta_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		$datos_para_cuadro = array();
		if ($where != '1=1') {
			$persona = $this->dep('filtro')->get_datos();
			$mostrar_historico = false;
			$datos = toba::consulta_php('co_designaciones')->get_designaciones_de_persona($where,$mostrar_historico);
			
			foreach ($datos as $dat) {
				$fila = $dat;
				if ($fila['designacion_tipo'] == 1 and $fila['designacion'] != null and ($fila['estado'] == 1 or $fila['estado'] == 5) ) {
					$horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
					$fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
				}

				if ($dat['estado'] == 1  or $dat['estado'] == 6) {
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