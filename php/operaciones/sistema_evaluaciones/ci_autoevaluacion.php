<?php
class ci_autoevaluacion extends planta_ci
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
	
    //-----------------------------------------------------------------------------------    
    //---- form_ficha -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ficha(planta_ei_formulario $form)
    {
        $fecha = new DateTime();
        $fecha->getTimestamp();

        $persona = toba::memoria()->get_dato('persona');
        $ciclo = toba::memoria()->get_dato('ciclo');
        $this->tabla('autoevaluaciones')->cargar(array('persona'=>$persona,'ciclo_lectivo'=>$ciclo));
        $datos = $this->tabla('autoevaluaciones')->get();

        // si esta cargada la ficha docente armo el link para descarga
        if ($datos['ficha_docente'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['ficha_docente_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['ficha_docente_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['ficha_docente_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
            $datos['archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  

            // si la ficha esta confirmada pongo en solo lectura el archivo y confirmado
            if ($datos['confirmado'] == 'S') {
                $form->ef('archivo')->set_solo_lectura(true);
                $perfil = toba::usuario()->get_perfiles_funcionales();
                if ($perfil[0] != 'admin' and $perfil[0] != 'usuario') { 
                    // a los admin siempre le permito modificar
                    $form->ef('confirmado')->set_solo_lectura(true); 
                }
                $form->evento('guardar')->desactivar();
            }  
            // si la ficha NO esta confirmada
            else {
                $this->evento('constancia')->desactivar();
                //$confirma_act = toba::consulta_php('co_autoevaluaciones')->get_actividades_sin_confirmar($persona,$ciclo);
                //if ($confirma_act[0]['autoeval_confirmado'] == 'N') {
                    // si todavia no confirmo sus actividades
                    //$form->ef('confirmado')->set_solo_lectura(true);
                //}                        
            }
        } else {
            // si la ficha NO esta cargada desactivamos el boton siguiente            
            $form->evento('siguiente')->desactivar();
            $this->evento('constancia')->desactivar();
        }
        $form->set_datos($datos);
    }

    function evt__form_ficha__guardar($datos)
    {
        $ciclo = toba::memoria()->get_dato('ciclo');
        $persona = toba::memoria()->get_dato('persona');

        if (isset($datos['archivo']) or isset($datos['ficha_docente_path'])) {
            $nombre_archivo = $datos['archivo']['name'];
            $doc = toba::consulta_php('co_personas')->get_datos_persona($persona);
            $nombre_nuevo = 'FD_'.$ciclo.'_'.$doc['documento'].'.pdf';   
            $destino = '/home/fce/informes/'.$nombre_nuevo;
            // Mover los archivos subidos al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['archivo']['tmp_name'], $destino); 

            $control['persona'] = $persona;
            $control['ficha_docente'] = 'S';  
            $control['ficha_docente_path'] = $destino;   
            $control['ciclo_lectivo'] = $ciclo;

            if ($datos['confirmado'] == 'S') {
                $control['confirmado'] = $datos['confirmado'];
                $control['confirmado_fecha'] = date('Y-m-d');
                $control['validacion'] = rand(10000,99999);
            }  

            $this->tabla('autoevaluaciones')->set($control);
            $this->tabla('autoevaluaciones')->sincronizar();
            $this->set_pantalla('pant_cuadro');
        }      
    }
	
    function evt__form_ficha__siguiente($datos)
    {
        $this->set_pantalla('pant_cuadro');
    }    
		
    //-----------------------------------------------------------------------------------
    //---- cuadro_act -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_act(planta_ei_cuadro $cuadro)
    {
        $persona = toba::memoria()->get_dato('persona');
        $nombre = toba::memoria()->get_dato('nombre');
        $cuadro->set_titulo('Docente: '.$nombre);
        $ciclo = toba::memoria()->get_dato('ciclo');
        $datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluaciones_por_act_persona($persona, $ciclo);

        $aux = array();
        foreach ($datos as $i) {
            if ($i['autoeval_confirmado'] == 'S') {
                $i['imagen'] = toba_recurso::imagen_toba('tilde.gif', true);
            } else {
                $i['imagen'] = toba_recurso::imagen_toba('vacio.png', true);
            }
            $aux[] = $i;
        }
        $cuadro->set_datos($aux);        
    }

    function evt__cuadro_act__seleccion($seleccion)
    {
        $this->tabla('asignaciones')->cargar($seleccion);
        $datos = $this->tabla('asignaciones')->get();

        if ($datos['responsable'] == 'S') {
            $this->set_pantalla('pant_form_resp');
        } else {
            $this->set_pantalla('pant_form');
        }        
    }

    function evt__cuadro_act__volver($datos)
    {
        $this->tabla('asignaciones')->resetear();
        $this->set_pantalla('pant_inicial');
    }

    //-----------------------------------------------------------------------------------
    //---- form_act ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_act(planta_ei_formulario $form)
    {   
        $nombre = toba::memoria()->get_dato('nombre');
        $form->set_titulo('Docente: '.$nombre);
        $datos = $this->tabla('asignaciones')->get();
        if ($datos['autoeval_confirmado'] == 'S') {
            $perfil = toba::usuario()->get_perfiles_funcionales();
            if ($perfil[0] != 'admin' and $perfil[0] != 'usuario') { 
                // a los admin siempre le permito modificar
                $form->evento('modificacion')->desactivar(); 
            }
        }
        $form->set_datos($datos);              
    }

    function evt__form_act__modificacion($datos)
    {
        if ($datos['autoeval_confirmado'] == 'S')
            $datos['autoeval_confirmado_fecha'] = date('Y-m-d');
        if ($datos['autoeval_calificacion'] != '')
            $datos['autoeval_calificacion_fecha'] = date('Y-m-d');
        $this->tabla('asignaciones')->set($datos);
        $this->tabla('asignaciones')->sincronizar();
        $this->tabla('asignaciones')->resetear();
        $this->set_pantalla('pant_cuadro');
    }

    function evt__form_act__cancelar()
    {
        $this->tabla('asignaciones')->resetear();
        $this->set_pantalla('pant_cuadro');
    }

    //-----------------------------------------------------------------------------------
    //---- form_resp --------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_resp(planta_ei_formulario $form)
    {
        $nombre = toba::memoria()->get_dato('nombre');
        $form->set_titulo('Docente: '.$nombre);
        $datos = $this->tabla('asignaciones')->get();

        if ($datos['dimension_desc'] != 'DO') {
            $form->ef('autoeval_informe_catedra_archivo')->set_solo_lectura(true);
            $form->ef('autoeval_programa_archivo')->set_solo_lectura(true);
        }
        if ($datos['autoeval_informe_catedra'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['autoeval_informe_catedra_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['autoeval_informe_catedra_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['autoeval_informe_catedra_path_2'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
            $datos['autoeval_informe_catedra_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';          
        } 
        if ($datos['autoeval_programa'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['autoeval_programa_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['autoeval_programa_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['autoeval_programa_path_2'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
            $datos['autoeval_programa_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';     
        }
        if ($datos['autoeval_informe_otros'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['autoeval_informe_otros_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['autoeval_informe_otros_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['autoeval_informe_otros_path_2'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
            $datos['autoeval_informe_otros_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';     
        }    
        if ($datos['autoeval_confirmado'] == 'S') {
            $perfil = toba::usuario()->get_perfiles_funcionales();
            if ($perfil[0] != 'admin' and $perfil[0] != 'usuario') { 
                // a los admin siempre le permito modificar
                $form->evento('modificacion')->desactivar();
            }
        }
        $form->set_datos($datos);
    }

    function evt__form_resp__modificacion($datos)
    {
        $ciclo = toba::memoria()->get_dato('ciclo');
        $persona = toba::memoria()->get_dato('persona');
        $doc = toba::consulta_php('co_personas')->get_datos_persona($persona);
        $datos_act = $this->tabla('asignaciones')->get();

        if (isset($datos['autoeval_informe_catedra_archivo']['name'])) {
            $nombre_archivo = $datos['autoeval_informe_catedra_archivo']['name'];
            $nombre_act = str_replace(' ','_',$datos_act['actividad_desc']);
            $nombre_act = str_replace('/','_',$nombre_act);
            $nombre_act = str_replace('º','_',$nombre_act);
            $info = new SplFileInfo($nombre_archivo);
            $nombre_nuevo = 'IC_'.$ciclo.'_'.$datos_act['ubicacion_desc'].'_'.$nombre_act. '.' .$info->getExtension();           
            $destino = '/home/fce/informes/'.$nombre_nuevo;
            // Mover los archivos subidos al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['autoeval_informe_catedra_archivo']['tmp_name'], $destino);           
            $datos['autoeval_informe_catedra'] = 'S';  
            $datos['autoeval_informe_catedra_path'] = $destino;   
            $datos['autoeval_tipo_informe'] = '';
        }
        if (isset($datos['autoeval_programa_archivo']['name'])) {
            $nombre_archivo = $datos['autoeval_programa_archivo']['name'];
            $nombre_act = str_replace(' ','_',$datos_act['actividad_desc']);
            $nombre_act = str_replace('/','_',$nombre_act);
            $nombre_act = str_replace('º','_',$nombre_act);
            $info = new SplFileInfo($nombre_archivo);
            $nombre_nuevo = 'PR_'.$ciclo.'_'.$datos_act['ubicacion_desc'].'_'.$nombre_act. '.' .$info->getExtension();          
            $destino = '/home/fce/informes/'.$nombre_nuevo;
            // Mover los archivos subidos al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['autoeval_programa_archivo']['tmp_name'], $destino);           
            $datos['autoeval_programa'] = 'S';  
            $datos['autoeval_programa_path'] = $destino;
            $datos['autoeval_tipo_informe'] = '';
        }
        if (isset($datos['autoeval_informe_otros_archivo']['name'])) {
            $nombre_archivo = $datos['autoeval_informe_otros_archivo']['name'];
            $nombre_act = str_replace(' ','_',$datos_act['actividad_desc']);
            $nombre_act = str_replace('/','_',$nombre_act);
            $nombre_act = str_replace('º','_',$nombre_act);
            $info = new SplFileInfo($nombre_archivo);
            $nombre_nuevo = 'IO_'.$ciclo.'_'.$datos_act['ubicacion_desc'].'_'.$datos['autoeval_tipo_informe'].'_'.  $nombre_act.'.' .$info->getExtension();         
            $destino = '/home/fce/informes/'.$nombre_nuevo;
            // Mover los archivos subidos al servidor del directorio temporal PHP a uno propio.
            move_uploaded_file($datos['autoeval_informe_otros_archivo']['tmp_name'], $destino);           
            $datos['autoeval_informe_otros'] = 'S';  
            $datos['autoeval_informe_otros_path'] = $destino;
        }
        $datos['ciclo_lectivo'] = $ciclo;
        if ($datos['autoeval_confirmado'] == 'S')
            $datos['autoeval_confirmado_fecha'] = date('Y-m-d');
        if ($datos['autoeval_calificacion'] != '')
            $datos['autoeval_calificacion_fecha'] = date('Y-m-d');

        $this->tabla('asignaciones')->set($datos);
        $this->tabla('asignaciones')->sincronizar();
        $this->tabla('asignaciones')->resetear();
        $this->set_pantalla('pant_cuadro');         
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_ficha(planta_ei_cuadro $cuadro)
    {
        $persona = toba::memoria()->get_dato('persona');
        $datos = toba::consulta_php('co_autoevaluaciones')->get_fichas_de_docente($persona);
        $cuadro->set_titulo('Ficha docente: '.$datos[0]['nombre_completo']);
        if (!isset($datos[0]['nombre_completo']))
            return;

        foreach ($datos as $dat) {
            $aux = $dat;
            if ($dat['ficha_docente_path'] != '') {
                  // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
                $nombre = substr($dat['ficha_docente_path'],19);
                $dir_tmp = toba::proyecto()->get_www_temp();
                exec("cp '". $dat['ficha_docente_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                $tamanio = round(filesize($temp_archivo['path']) / 1024);
                $aux['archivo'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                #$datos['archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';                          
            }
            $datos_aux[] = $aux;
        }            
        $cuadro->set_datos($datos_aux);

    }

    function evt__cuadro_ficha__seleccion($seleccion)
    {
        toba::memoria()->set_dato('path',$seleccion['ficha_docente_path']);
    }        
	
    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ficha_descarga(planta_ei_formulario $form)
    {
        $path = toba::memoria()->get_dato('path'); 
        if (!isset($path))
            return;  
        // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
        $nombre = substr($path,19);
        $dir_tmp = toba::proyecto()->get_www_temp();
        exec("cp '". $path. "' '" .$dir_tmp['path']."/".$nombre."'");
        $temp_archivo = toba::proyecto()->get_www_temp($nombre);
        $tamanio = round(filesize($temp_archivo['path']) / 1024);
        $datos['ficha_docente_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";

        $form->set_datos($datos);         
    }        

    function evt__form_resp__cancelar()
    {
        $this->dep('relacion')->resetear();
        $this->set_pantalla('pant_cuadro');
    }

    function get_tipo_informe()
    {
        $datos_act = $this->tabla('asignaciones')->get();
        $datos_asig = toba::consulta_php('co_asignaciones')->get_asignacion_tabla($datos_act['asignacion']); 
        $ambito = $datos_asig['ambito'];
        $dimension = $datos_asig['dimension'];
        $datos = toba::consulta_php('co_autoevaluaciones')->get_tipo_informe_por_dim($dimension,$ambito); 
        return $datos;
    }
	
    //-----------------------------------------------------------------------------------
    //---- ------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    function vista_jasperreports(toba_vista_jasperreports $report) 
    {    
        $report->set_nombre_archivo('Constancia de autoevalacion');
        $persona = toba::memoria()->get_dato('persona');
        $report->set_parametro('docente','E',$persona);  
        $path_toba = toba::proyecto()->get_path().'/exportaciones/jasper/';
        $path = $path_toba.'constancia_autoeval.jasper';
        $report->set_path_reporte($path);
        $report->completar_con_datos();
    }
}
?>