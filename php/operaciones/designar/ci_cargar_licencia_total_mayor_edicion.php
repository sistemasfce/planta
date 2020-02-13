<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class ci_cargar_licencia_total_mayor_edicion extends planta_ci
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
        $aux = $datos;
        
        //cargo los datos de la licencia
        $aux['persona'] = $datos_origen['persona'];
        $aux['espacio_disciplinar'] = $datos_origen['espacio_disciplinar'];
        $aux['departamento'] = $datos_origen['departamento'];
        $aux['dedicacion'] = $datos_origen['dedicacion'];
        $aux['categoria'] = $datos_origen['categoria'];        
        $aux['caracter'] = $datos_origen['caracter'];
        $aux['ubicacion'] = $datos_origen['ubicacion'];
        $aux['carrera_academica'] = $datos_origen['carrera_academica'];
        $aux['carga_horaria'] = $datos_origen['carga_horaria'];
        $this->tabla('designaciones')->nueva_fila($aux);
        
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);
        $fila['designacion_anterior'] = $seleccion['designacion'];
        $fila['apex_ei_analisis_fila'] = 'A';
        $this->tabla('designaciones_modificadas')->nueva_fila($fila);
        
        $aux = $datos;        
        //cargo los datos de la interina
        $aux['persona'] = $datos_origen['persona'];
        //$aux['espacio_disciplinar'] = $datos_origen['espacio_disciplinar'];
        //$aux['departamento'] = $datos_origen['departamento'];
        $aux['caracter'] = comunes::car_interino;
        //$aux['ubicacion'] = $datos_origen['ubicacion'];
        $aux['carrera_academica'] = 'N';
        //$aux['carga_horaria'] = $datos_origen['carga_horaria'];
        $aux['designacion_tipo'] = comunes::desig_alta;
        $aux['estado'] = comunes::estado_activo;
        $this->tabla('designaciones')->nueva_fila($aux);      
        
        $completo = $this->tabla('designaciones')->get_filas();
        $this->tabla('designaciones')->set_cursor(count($completo)-1);
        $fila['designacion_anterior'] = $seleccion['designacion'];
        $fila['apex_ei_analisis_fila'] = 'A';
        $this->tabla('designaciones_modificadas')->nueva_fila($fila);          
    }
}
?>