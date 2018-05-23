<?php
class ci_autoevaluaciones_por_docente extends planta_ci
{
    protected $s__filtro;
    protected $s__filtro_d;
    
    function conf()
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario_inv' and $perfil[0] != 'usuario') {
            $this->pantalla('pant_inicial')->eliminar_dep('filtro');
        } else {
            $this->pantalla('pant_inicial')->eliminar_dep('filtro_delegado');
        }
        
        $this->pantalla('pant_inicial')->eliminar_dep('form_resp');
        $this->pantalla('pant_inicial')->eliminar_dep('form_act');   
    }
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario_inv' and $perfil[0] != 'usuario') {
            $where = $this->dep('filtro_delegado')->get_sql_where();  
            $datos_filtro = $this->dep('filtro_delegado')->get_datos();
            $ciclo_array = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
            $ciclo = $ciclo_array['valor_num'];
        } else {
            $where = $this->dep('filtro')->get_sql_where();  
            $datos_filtro = $this->dep('filtro')->get_datos();
            $ciclo = $datos_filtro['ciclo_lectivo']['valor'];
        }
        if ($where == '1=1')
                return;
        $persona = $datos_filtro['persona']['valor'];
        $datos[0] = toba::consulta_php('co_autoevaluaciones')->get_ficha_de_docente($persona,$ciclo);
        $cuadro->set_titulo('Ficha docente: '.$datos[0]['nombre_completo']);
        if (!isset($datos[0]['nombre_completo']))
                return;
        $cuadro->set_datos($datos);
    }

    function evt__cuadro__seleccion($seleccion)
    {
        toba::memoria()->set_dato('path',$seleccion['ficha_docente_path']);
    }
	
    //-----------------------------------------------------------------------------------
    //---- cuadro_act -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_act(planta_ei_cuadro $cuadro)
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario_inv' and $perfil[0] != 'usuario') {
            $where = $this->dep('filtro_delegado')->get_sql_where();  
            $datos_filtro = $this->dep('filtro_delegado')->get_datos();
            $ciclo_array = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
            $ciclo = $ciclo_array['valor_num'];
        } else {
            $where = $this->dep('filtro')->get_sql_where();  
            $datos_filtro = $this->dep('filtro')->get_datos();
            $ciclo = $datos_filtro['ciclo_lectivo']['valor'];
        }
        if ($where == '1=1')
            return;
        $persona = $datos_filtro['persona']['valor'];
        $datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluaciones_por_act_persona($persona,$ciclo);
        $cuadro->set_titulo('Autoevaluaciones: '.$datos[0]['nombre_completo']);
        $cuadro->set_datos($datos);        
    }

    function evt__cuadro_act__seleccion($seleccion)
    {
        toba::memoria()->set_dato('asignacion',$seleccion['asignacion']); 
        if ($seleccion['responsable'] == 'S') {
            // es responsable
            $this->pantalla('pant_inicial')->agregar_dep('form_resp');
        } else {
            // NO es responsable
            $this->pantalla('pant_inicial')->agregar_dep('form_act');
        }
    }

    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(planta_ei_formulario $form)
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
	
    //-----------------------------------------------------------------------------------
    //---- form_act ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_act(planta_ei_formulario $form)
    {
        $asignacion = toba::memoria()->get_dato('asignacion'); 
        if (!isset($asignacion))
            return;
        $datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);
        $form->set_datos($datos); 
    }

    //-----------------------------------------------------------------------------------
    //---- form_resp ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_resp(planta_ei_formulario $form)
    {
        $asignacion = toba::memoria()->get_dato('asignacion'); 
        if (!isset($asignacion))
            return;
        $datos = toba::consulta_php('co_autoevaluaciones')->get_autoevaluacion_por_asignacion($asignacion);

        if ($datos['autoeval_informe_catedra'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['autoeval_informe_catedra_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['autoeval_informe_catedra_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['autoeval_informe_catedra_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";         
        } 
        if ($datos['autoeval_programa'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['autoeval_programa_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['autoeval_programa_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['autoeval_programa_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";    
        }
        if ($datos['autoeval_informe_otros'] == 'S') {
            // el 19 es para que corte la cadena despues del caracter 19, de /home/fce/informes/
            $nombre = substr($datos['autoeval_informe_otros_path'],19);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['autoeval_informe_otros_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['autoeval_informe_otros_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";    
        }
        $form->set_datos($datos); 
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
    
    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro_delegado(planta_ei_filtro $filtro)
    {
        if (isset($this->s__filtro_d)) {
            $filtro->set_datos($this->s__filtro_d);
        }
    }

    function evt__filtro_delegado__filtrar($datos)
    {
        $this->s__filtro_d = $datos;
    }

    function evt__filtro_delegado__cancelar()
    {
        unset($this->s__filtro_d);
    }    
    
    
    function get_docentes()
    {
        $documento = toba::usuario()->get_id();
        $persona = toba::consulta_php('co_personas')->get_id($documento);
        $datos = toba::consulta_php('co_asignaciones')->get_docentes_por_sede($persona['persona']);
        return $datos;
    }
    
}
?>