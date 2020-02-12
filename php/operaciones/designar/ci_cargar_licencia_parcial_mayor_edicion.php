<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class ci_cargar_licencia_parcial_mayor_edicion extends planta_ci
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
        toba::memoria()->set_dato('seleccion',$seleccion);
        $this->pantalla('pant_inicial')->agregar_dep('form_des');
    }
   
    function evt__form_des__modificacion($datos)
    {   
        $this->hay_cambios = true; 
        $seleccion = toba::memoria()->get_dato('seleccion');  
        $datos_origen = toba::consulta_php('co_designaciones')->get_designacion($seleccion['designacion']);       
        $aux = $datos_origen;
        
        //cargo los datos de la licencia
        $aux['dedicacion'] = $datos['dedicacion'];
        $aux['carga_horaria'] = $datos['carga_horaria'];  
        $aux['fecha_desde'] = $datos['fecha_desde'];  
        $aux['fecha_hasta'] = $datos['fecha_hasta']; 
        $aux['observaciones'] = $datos['observaciones'];  
        $aux['resolucion'] = $datos['resolucion'];
        $aux['resolucion_anio'] = $datos['resolucion_anio'];
        $aux['resolucion_fecha'] = $datos['resolucion_fecha'];
        $aux['resolucion_tipo'] = $datos['resolucion_tipo']; 
        $aux['designacion_tipo'] = $datos['designacion_tipo'];    
        $aux['estado'] = $datos['estado'];   
        $this->tabla('designaciones')->nueva_fila($aux);
        
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);
        $fila['designacion_anterior'] = $seleccion['designacion'];
        $fila['apex_ei_analisis_fila'] = 'A';
        $this->tabla('designaciones_modificadas')->nueva_fila($fila);
        
        $aux = $datos_origen; 
        //cargo los datos de la regular
        $aux['dedicacion'] = $datos['reg_dedicacion'];
        $aux['carga_horaria'] = $datos['reg_carga_horaria'];   
        $aux['fecha_desde'] = $datos['fecha_desde'];  
        $aux['fecha_hasta'] = $datos['fecha_hasta']; 
        $aux['resolucion'] = $datos['reg_resolucion'];
        $aux['resolucion_anio'] = $datos['reg_resolucion_anio'];
        $aux['resolucion_fecha'] = $datos['reg_resolucion_fecha'];
        $aux['resolucion_tipo'] = $datos['reg_resolucion_tipo']; 
        $aux['designacion_tipo'] = $datos['reg_designacion_tipo'];    
        $aux['estado'] = $datos['reg_estado'];   
        $this->tabla('designaciones')->nueva_fila($aux);
        
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);
        $fila['designacion_anterior'] = $seleccion['designacion'];
        $fila['apex_ei_analisis_fila'] = 'A';
        $this->tabla('designaciones_modificadas')->nueva_fila($fila);       
        
        $aux = $datos_origen;        
        //cargo los datos de la interina
        $aux['espacio_disciplinar'] = $datos['int_espacio_disciplinar'];
        $aux['departamento'] = $datos['int_departamento'];
        $aux['caracter'] = comunes::car_interino;
        $aux['ubicacion'] = $datos['int_ubicacion'];
        $aux['categoria'] = $datos['int_categoria'];
        $aux['carga_horaria'] = $datos['int_carga_horaria'];
        $aux['fecha_desde'] = $datos['fecha_desde'];  
        $aux['fecha_hasta'] = $datos['fecha_hasta'];         
        $aux['dedicacion'] = $datos['int_dedicacion'];
        $aux['dimension'] = $datos['int_dimension'];
        $aux['resolucion'] = $datos['int_resolucion'];
        $aux['resolucion_anio'] = $datos['int_resolucion_anio'];
        $aux['resolucion_fecha'] = $datos['int_resolucion_fecha'];
        $aux['resolucion_tipo'] = $datos['int_resolucion_tipo'];
        $aux['carrera_academica'] = 'N';
        $aux['designacion_tipo'] = $datos['int_designacion_tipo'];
        $aux['estado'] = $datos['int_estado'];
        $this->tabla('designaciones')->nueva_fila($aux);
        
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);
        $fila['designacion_anterior'] = $seleccion['designacion'];
        $fila['apex_ei_analisis_fila'] = 'A';
        $this->tabla('designaciones_modificadas')->nueva_fila($fila);        
    }
}
?>