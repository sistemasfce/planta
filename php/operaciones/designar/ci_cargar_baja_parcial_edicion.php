<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class ci_cargar_baja_parcial_edicion extends planta_ci
{
    protected $hay_cambios;

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

    function get_hay_cambios()
    {
        return $this->hay_cambios;
    }
    
    function set_hay_cambios($valor)
    {
        $this->hay_cambios = $valor;
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
            
            // si es historico no lo muestro
            if ($dat['estado'] != comunes::estado_activo) {
                continue;
            }  
            if ($dat['dedicacion'] != comunes::ded_semi and $dat['dedicacion'] != comunes::ded_exc and $dat['dedicacion'] != comunes::ded_semi_sim and $dat['dedicacion'] != comunes::ded_exc_sim) {
                continue;
            }
            
            $fila = $dat;
            $fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

            if ($fila['designacion_tipo'] == comunes::desig_alta and $fila['designacion'] != null and ($fila['estado'] == comunes::estado_activo or $fila['estado'] == comunes::estado_con_licencia_p) ) {
                $horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
                $fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
            }
            $fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
            $datos_para_cuadro[] = $fila;
        }
        $datos_ordenados = rs_ordenar_por_columna($datos_para_cuadro, 'resolucion_fecha');
        $cuadro->set_datos($datos_ordenados);
    }
	
    function evt__cuadro_des__seleccion($seleccion)
    {
        $this->tabla('designaciones')->set_cursor($seleccion);
        toba::memoria()->set_dato('seleccion',$seleccion); 
        $this->hay_cambios = true;  
    }    

    //-----------------------------------------------------------------------------------
    //---- form_des ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_des(planta_ei_formulario $form)
    {
        if ($this->tabla('designaciones')->hay_cursor()) {
            $datos = $this->tabla('designaciones')->get();
            $datos['estado'] = comunes::estado_historico;
            $datos['designacion_tipo'] = comunes::desig_baja;
            $datos['fecha_desde'] = '';
            $datos['fecha_hasta'] = '';
            $datos['resolucion'] = '';
            $datos['resolucion_fecha'] = '';
            $datos['resolucion_anio'] = '';
            $form->set_datos($datos);
        }        
    }
    
    function evt__form_des__modificacion($datos)
    {
        $datos['nombre_completo'] = '';
        // cargo la baja parcial y la relaciono
        $this->tabla('designaciones')->nueva_fila($datos);
        
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);
        $seleccionados = toba::memoria()->get_dato('seleccion');     
        foreach ($seleccionados as $sel) {
            foreach ($completo as $co) {
                if ($sel['x_dbr_clave'] == $co['x_dbr_clave']) {
                    $fila['designacion_anterior'] = $co['designacion'];
                    $fila['apex_ei_analisis_fila'] = 'A';
                    $this->tabla('designaciones_modificadas')->nueva_fila($fila);
                }
            }
        }
        // cargo la modificacion y la relaciono
        $datos['designacion_tipo'] = comunes::desig_modifica;
        $datos['estado'] = comunes::estado_activo;
        $this->tabla('designaciones')->nueva_fila($datos);
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);    
        foreach ($seleccionados as $sel) {
            foreach ($completo as $co) {
                if ($sel['x_dbr_clave'] == $co['x_dbr_clave']) {                        
                    $fila['designacion_anterior'] = $co['designacion'];
                    $fila['apex_ei_analisis_fila'] = 'A';
                    $this->tabla('designaciones_modificadas')->nueva_fila($fila);
                }
            }
        }       
    }
}
?>