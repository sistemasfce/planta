<?php
class ci_historicos extends planta_ci
{  
    //-------------------------------------------------------------------------
    function relacion()
    {
        return $this->dep('relacion');
    }
    
    //-------------------------------------------------------------------------
    function tabla($id)
    {
        return $this->dep('relacion')->tabla($id);    
    }
    
    function ini()
    {
        $persona['persona'] = toba::memoria()->get_dato('persona');
        $this->relacion()->cargar($persona);
    }
    
    //-----------------------------------------------------------------------------------
    //---- AUTOEVAL ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_autoeval(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $datos = $this->tabla('asignaciones_hist')->get_filas();
        $ciclo = toba::memoria()->get_dato('ciclo');
        $datos_para_cuadro = array();
        
        foreach ($datos as $dat) {
            if ($dat['ciclo_lectivo'] == $ciclo)
                continue;  
            if ($dat['autoeval_estado'] == 1) // si el estado es activo
                $datos_para_cuadro[] = $dat;
            /*
            if ($dat['autoeval_estado'] == 13) // si el estado es no aplica
                $datos_para_cuadro[] = $dat;   
            if ($dat['autoeval_estado'] == 14) // si el estado es no corresponde
                $datos_para_cuadro[] = $dat;
             */
        }
        
        $datos_ordenados = rs_ordenar_por_columna($datos_para_cuadro, 'ciclo_lectivo');
        $cuadro->set_datos($datos_ordenados);
        $cuadro->set_titulo('Autoevaluaciones del docente: '.$datos_ordenados[0]['nombre_completo']);
    }
    
    function evt__cuadro_autoeval__seleccion($seleccion)
    {
        $this->tabla('asignaciones_hist')->set_cursor($seleccion);
    }    
    
    //-----------------------------------------------------------------------------------
    //---- form_des ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_autoeval(planta_ei_formulario $form)
    {
        if ($this->tabla('asignaciones_hist')->hay_cursor()) {
            $datos = $this->tabla('asignaciones_hist')->get();    

            if ($datos['autoeval_informe_catedra'] == 'S') {
                    // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
                    $nombre = substr($datos['autoeval_informe_catedra_path'],19);
                    $dir_tmp = toba::proyecto()->get_www_temp();
                    exec("cp '". $datos['autoeval_informe_catedra_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                    $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                    $tamanio = round(filesize($temp_archivo['path']) / 1024);
                    $datos['autoeval_informe_catedra_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                    $datos['autoeval_informe_catedra_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';          
            } 
            if ($datos['autoeval_programa'] == 'S') {
                    // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
                    $nombre = substr($datos['autoeval_programa_path'],19);
                    $dir_tmp = toba::proyecto()->get_www_temp();
                    exec("cp '". $datos['autoeval_programa_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                    $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                    $tamanio = round(filesize($temp_archivo['path']) / 1024);
                    $datos['autoeval_programa_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                    $datos['autoeval_programa_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';     
            }
            if ($datos['autoeval_informe_otros'] == 'S') {
                    // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
                    $nombre = substr($datos['autoeval_informe_otros_path'],19);
                    $dir_tmp = toba::proyecto()->get_www_temp();
                    exec("cp '". $datos['autoeval_informe_otros_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                    $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                    $tamanio = round(filesize($temp_archivo['path']) / 1024);
                    $datos['autoeval_informe_otros_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                    $datos['autoeval_informe_otros_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';     
            }            
            
            
            $form->set_datos($datos);
        }        
    }
    
    //-----------------------------------------------------------------------------------
    //---- EVAL ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_eval(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $datos = $this->tabla('asignaciones_hist')->get_filas();
        $ciclo = toba::memoria()->get_dato('ciclo');
        $datos_para_cuadro = array();
        
        foreach ($datos as $dat) {
            if ($dat['ciclo_lectivo'] == $ciclo)
                continue;  
            if ($dat['autoeval_estado'] == 1) // si el estado es activo
                $datos_para_cuadro[] = $dat;
        }
        $datos_ordenados = rs_ordenar_por_columna($datos_para_cuadro, 'ciclo_lectivo');
        $cuadro->set_datos($datos_ordenados);
        $cuadro->set_titulo('Evaluaciones del docente: '.$datos_ordenados[0]['nombre_completo']);

    }
    
    function evt__cuadro_eval__seleccion($seleccion)
    {
        $this->tabla('asignaciones_hist')->set_cursor($seleccion);
    }    
    
    //-----------------------------------------------------------------------------------
    //---- form_des ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_eval(planta_ei_formulario $form)
    {
        if ($this->tabla('asignaciones_hist')->hay_cursor()) {
            $datos = $this->tabla('asignaciones_hist')->get();    
            $form->set_datos($datos);
        }        
    }   
}

