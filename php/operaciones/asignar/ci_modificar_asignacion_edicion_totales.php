<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class ci_modificar_asignacion_edicion_totales extends planta_ci
{
    protected $hay_cambios;
    protected $designaciones_disponibles;
    protected $datos_para_cuadro = array();

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

    function ini()
    {
        $this->hay_cambios = false;    
    }   

    //-----------------------------------------------------------------------------------
    //---- cuadro_des ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_des(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $aux = array();
        $datos = $this->tabla('designaciones')->get_filas();

        foreach ($datos as $dat) {
            $fila = $dat;          
            $fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

            if ($fila['designacion_tipo'] == comunes::desig_alta and $fila['designacion'] != null and ($fila['estado'] == comunes::estado_activo or $fila['estado'] == comunes::estado_con_licencia_p)) {
                $horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
                $fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
            }

            $suma_asignaciones = toba::consulta_php('co_asignaciones')->get_horas_asignadas_x_designacion($fila['designacion']);
            $fila['a_definir'] = $fila['carga_horaria_real'] - $suma_asignaciones['suma'];

            if ($dat['estado'] == comunes::estado_activo  or $dat['estado'] == comunes::estado_vigente) {
                $fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
            }
            if ($dat['estado'] == comunes::estado_historico) {
                $fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
            }  else {
                $fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
            }
            $this->datos_para_cuadro[] = $fila;
        }
        $datos_ordenados = rs_ordenar_por_columna($this->datos_para_cuadro, 'resolucion_fecha');
        $this->datos_para_cuadro = $datos_ordenados;
        $cuadro->set_datos($datos_ordenados);
    }  

    function evt__cuadro_des__seleccion($seleccion)
    {
        $this->tabla('designaciones')->set_cursor($seleccion);
        $this->hay_cambios = true; 
    } 

    //-----------------------------------------------------------------------------------
    //---- cuadro_asig ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_asig(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $aux = array();
        $datos = $this->tabla('asignaciones')->get_filas();
        foreach ($datos as $dat) {
            $fila = $dat;
            $fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

            if ($dat['estado'] == comunes::estado_activo  or $dat['estado'] == comunes::estado_vigente) {
                $fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
            }
            if ($dat['estado'] == comunes::estado_historico) {
                $fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
            }  else {
                $fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
            }
            $datos_para_cuadro[] = $fila;
        }
        $datos_ordenados = rs_ordenar_por_columna($datos_para_cuadro, 'resolucion_fecha');
        $cuadro->set_datos($datos_ordenados);
    }

    function evt__cuadro_asig__seleccion($seleccion)
    {
        $this->tabla('asignaciones')->set_cursor($seleccion);
    }
	
    //-----------------------------------------------------------------------------------
    //---- form_asig ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_asig(planta_ei_formulario $form)
    {
        if ($this->tabla('asignaciones')->hay_cursor()) {
            $datos_des =$this->tabla('designaciones')->get();
            $datos = $this->tabla('asignaciones')->get();
            $datos['carrera_academica'] = $datos_des['carrera_academica'];
            $form->set_datos($datos);
        }      
    }

    function evt__form_asig__baja()
    {
        $this->tabla('asignaciones')->set(null);
    }

    function evt__form_asig__modificacion($datos)
    {
        $datos['cambia_estado'] = 'S';
        $this->tabla('asignaciones')->set($datos);
        $this->evt__form_asig__cancelar();
    }

    function evt__form_asig__cancelar()
    {
        $this->tabla('asignaciones')->resetear_cursor();
    }

    //-----------------------------------------------------------------------------------
    //-------------- --------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function get_hay_cambios()
    {
        return $this->hay_cambios;
    }  

    function get_designaciones_disponibles()
    {
        $designaciones_disponibles[0]['designacion'] = 1;
        $designaciones_disponibles[0]['descripcion'] = 1;
        return $this->designaciones_disponibles;
    }    
}
?>