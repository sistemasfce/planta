<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class ci_cargar_licencia_total_edicion extends planta_ci
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

    //-----------------------------------------------------------------------------------
    //---- cuadro_des ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_des(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $datos = $this->tabla('designaciones')->get_filas();

        foreach ($datos as $dat) {
            
            // si es historico no lo muestro
            if ($dat['estado'] != comunes::estado_activo) {
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

    function evt__form_des__modificacion($datos)
    {   
        if ($this->tabla('designaciones')->hay_cursor()) {
            $datos_origen = $this->tabla('designaciones')->get();
            $datos['persona'] = $datos_origen['persona'];
            $datos['espacio_disciplinar'] = $datos_origen['espacio_disciplinar'];
            $datos['departamento'] = $datos_origen['departamento'];
            $datos['categoria'] = $datos_origen['categoria'];
            $datos['caracter'] = $datos_origen['caracter'];
            $datos['ubicacion'] = $datos_origen['ubicacion'];
            $datos['carrera_academica'] = $datos_origen['carrera_academica'];
            $datos['carga_horaria'] = $datos_origen['carga_horaria'];
            $this->tabla('designaciones')->nueva_fila($datos);
            ei_arbol($datos);
        }
        
    }

}
?>