<?php
class ci_pendientes extends planta_ci
{
    protected $s__filtro;

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_dim(planta_ei_cuadro $cuadro)
    {
        $parametro_ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
        $ciclo =  $parametro_ciclo['valor_num'];
        $por_persona = 1;
        $datos = $this->obtener_matriz($ciclo,$por_persona);
        //$cuadro->set_titulo('Docentes que no subieron o no confirmaron ficha docente');
        $cuadro->set_datos($datos);       
    }        

    function conf__cuadro_dim_act(planta_ei_cuadro $cuadro)
    {
        $parametro_ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
        $ciclo =  $parametro_ciclo['valor_num'];
        $por_persona = 0;
        $datos = $this->obtener_matriz($ciclo,$por_persona);
        //$cuadro->set_titulo('Docentes que no subieron o no confirmaron ficha docente');
        $cuadro->set_datos($datos);
    }
    
    function conf__cuadro_sede(planta_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();  
        if ($where == '1=1')
                return;
        
        $parametro_ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
        $ciclo =  $parametro_ciclo['valor_num'];
        $filtro = $this->dep('filtro')->get_datos();
        $dimension = $filtro['dimension']['valor'];
        $por_persona = 1;
        
        $datos = toba::consulta_php('co_autoevaluaciones')->get_matriz_por_sede($ciclo,$dimension,$por_persona);
        $cuadro->set_datos($datos); 
    }
    
    function conf__cuadro_sede_act(planta_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();  
        if ($where == '1=1')
                return;
        
        $parametro_ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
        $ciclo =  $parametro_ciclo['valor_num'];
        $filtro = $this->dep('filtro')->get_datos();
        $dimension = $filtro['dimension']['valor'];
        $por_persona = 0;
        
        $datos = toba::consulta_php('co_autoevaluaciones')->get_matriz_por_sede($ciclo,$dimension,$por_persona);
        $cuadro->set_datos($datos); 
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
    
    function obtener_matriz($ciclo, $por_persona)
    {
        $where = '1=1';
        $sin_ficha=0;
        $path = toba::proyecto()->get_www();
        
        $total_personas = toba::consulta_php('co_autoevaluaciones')->get_cantidad_fichas($ciclo);
        $pendientes = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo);
        foreach ($pendientes as $pen) {
            if ($pen['confirmado'] == '')
                $sin_ficha+=1;
        }        
        $dimension = 1;
        $total_docencia = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,$por_persona);
        $sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',$por_persona); 
        $eval_sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',$por_persona);        
        $eval_notificado = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,$por_persona);
        $datos[0]['dimension'] = 'Docencia';
        $datos[0]['ficha_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=1 target='_blank'>".$total_personas['count']."</a>"; 
        $datos[0]['ficha_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=2 target='_blank'>".$sin_ficha."</a>"; 
        $datos[0]['ficha_pen_porc'] = $sin_ficha / $total_personas['count'] * 100;      
        $datos[0]['ficha_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=3 target='_blank'>".count($pendientes)."</a>"; 
        $datos[0]['ficha_pen_conf_porc'] = count($pendientes) / $total_personas['count'] * 100; 
        
        $datos[0]['auto_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=4 target='_blank'>".$total_docencia['count']."</a>";
        $datos[0]['auto_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=5 target='_blank'>".$sin_hacer_docencia['count']."</a>";
        $datos[0]['auto_pen_porc'] = $sin_hacer_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['auto_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=6 target='_blank'>".$sin_confirmar_docencia['count']."</a>"; 
        $datos[0]['auto_pen_conf_porc'] = $sin_confirmar_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['eval_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=7 target='_blank'>".$total_docencia['count']."</a>";
        $datos[0]['eval_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=8 target='_blank'>".$eval_sin_hacer_docencia['count']."</a>";
        $datos[0]['eval_pen_porc'] = $eval_sin_hacer_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['eval_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=9 target='_blank'>".$eval_sin_confirmar_docencia['count']."</a>";   
        $datos[0]['eval_pen_conf_porc'] = $eval_sin_confirmar_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['noti_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=10 target='_blank'>".$total_docencia['count']."</a>";
        $datos[0]['noti_notificados'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=11 target='_blank'>".$eval_notificado['count']."</a>";
        $datos[0]['noti_notificados_porc'] = $eval_notificado['count'] / $total_docencia['count'] * 100;
        
        $dimension = 2;
        $total_ext = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,$por_persona);
        $sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $eval_sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',$por_persona);        
        $eval_notificado_ext = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,$por_persona);
        $datos[1]['dimension'] = 'Extensión';
        $datos[1]['auto_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=4 target='_blank'>".$total_ext['count']."</a>";
        $datos[1]['auto_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=5 target='_blank'>".$sin_hacer_ext['count']."</a>";
        $datos[1]['auto_pen_porc'] = $sin_hacer_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['auto_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=6 target='_blank'>".$sin_confirmar_ext['count']."</a>";
        $datos[1]['auto_pen_conf_porc'] = $sin_confirmar_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['eval_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=7 target='_blank'>".$total_ext['count']."</a>";
        $datos[1]['eval_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=8 target='_blank'>".$eval_sin_hacer_ext['count']."</a>";
        $datos[1]['eval_pen_porc'] = $sin_hacer_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['eval_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=9 target='_blank'>".$eval_sin_confirmar_ext['count']."</a>";
        $datos[1]['eval_pen_conf_porc'] = $eval_sin_confirmar_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['noti_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=10 target='_blank'>".$total_ext['count']."</a>";
        $datos[1]['noti_notificados'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=2&columna=11 target='_blank'>".$eval_notificado_ext['count']."</a>";
        $datos[1]['noti_notificados_porc'] = $eval_notificado_ext['count'] / $total_ext['count'] * 100;       
        
        $dimension = 3;
        $total_inv = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,$por_persona);
        $sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $eval_sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_notificado_inv = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,$por_persona);
        $datos[2]['dimension'] = 'Investigación';
        $datos[2]['auto_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=4 target='_blank'>".$total_inv['count']."</a>";
        $datos[2]['auto_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=5 target='_blank'>".$sin_hacer_inv['count']."</a>";
        $datos[2]['auto_pen_porc'] = $sin_hacer_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['auto_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=6 target='_blank'>".$sin_confirmar_inv['count']."</a>";
        $datos[2]['auto_pen_conf_porc'] = $sin_confirmar_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['eval_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=7 target='_blank'>".$total_inv['count']."</a>";
        $datos[2]['eval_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=8 target='_blank'>".$eval_sin_hacer_inv['count']."</a>";
        $datos[2]['eval_pen_porc'] = $eval_sin_hacer_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['eval_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=9 target='_blank'>".$eval_sin_confirmar_inv['count']."</a>";
        $datos[2]['eval_pen_conf_porc'] = $sin_confirmar_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['noti_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=10 target='_blank'>".$total_inv['count']."</a>";
        $datos[2]['noti_notificados'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=3&columna=11 target='_blank'>".$eval_notificado_inv['count']."</a>";
        $datos[2]['noti_notificados_porc'] = $eval_notificado_inv['count'] / $total_inv['count'] * 100;
        
        $dimension = 4;
        $total_gestion = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,$por_persona);
        $sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $eval_sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_notificado_gestion = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,$por_persona);
        $datos[3]['dimension'] = 'Gestión';
        $datos[3]['auto_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=4 target='_blank'>".$total_gestion['count']."</a>";
        $datos[3]['auto_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=5 target='_blank'>".$sin_hacer_gestion['count']."</a>";
        $datos[3]['auto_pen_porc'] = $sin_hacer_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['auto_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=6 target='_blank'>".$sin_confirmar_gestion['count']."</a>";
        $datos[3]['auto_pen_conf_porc'] = $sin_confirmar_gestion['count'] / $total_gestion['count'] * 100;        
        $datos[3]['eval_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=7 target='_blank'>".$total_gestion['count']."</a>";
        $datos[3]['eval_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=8 target='_blank'>".$eval_sin_hacer_gestion['count']."</a>";
        $datos[3]['eval_pen_porc'] = $eval_sin_hacer_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['eval_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=9 target='_blank'>".$eval_sin_confirmar_gestion['count']."</a>";    
        $datos[3]['eval_pen_conf_porc'] = $eval_sin_confirmar_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['noti_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=10 target='_blank'>".$total_gestion['count']."</a>";
        $datos[3]['noti_notificados'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=4&columna=11 target='_blank'>".$eval_notificado_gestion['count']."</a>";
        $datos[3]['noti_notificados_porc'] = $eval_notificado_gestion['count'] / $total_gestion['count'] * 100;
        
        $dimension = 5;
        $total_for = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,$por_persona);
        $sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',$por_persona);       
        $eval_sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',$por_persona);                
        $eval_notificado_for = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,$por_persona);
        $datos[4]['dimension'] = 'Formación';
        $datos[4]['auto_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=4 target='_blank'>".$total_for['count']."</a>";
        $datos[4]['auto_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=5 target='_blank'>".$sin_hacer_for['count']."</a>";
        $datos[4]['auto_pen_porc'] = $sin_hacer_for['count'] / $total_for['count'] * 100;
        $datos[4]['auto_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=6 target='_blank'>".$sin_confirmar_for['count']."</a>";  
        $datos[4]['auto_pen_conf_porc'] = $sin_confirmar_for['count'] / $total_for['count'] * 100;
        $datos[4]['eval_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=7 target='_blank'>".$total_for['count']."</a>";
        $datos[4]['eval_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=8 target='_blank'>".$eval_sin_hacer_for['count']."</a>";
        $datos[4]['eval_pen_porc'] = $eval_sin_hacer_for['count'] / $total_for['count'] * 100;
        $datos[4]['eval_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=9 target='_blank'>".$eval_sin_confirmar_for['count']."</a>";  
        $datos[4]['eval_pen_conf_porc'] = $eval_sin_confirmar_for['count'] / $total_for['count'] * 100;        
        $datos[4]['noti_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=10 target='_blank'>".$total_for['count']."</a>";
        $datos[4]['noti_notificados'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=5&columna=11 target='_blank'>".$eval_notificado_for['count']."</a>";
        $datos[4]['noti_notificados_porc'] = $eval_notificado_for['count'] / $total_for['count'] * 100;
        
        $dimension = 0;
        $total_noc = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,$por_persona);
        $sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',$por_persona);
        $sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',$por_persona);   
        $eval_sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',$por_persona);
        $eval_sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',$por_persona);        
        $eval_notificado_noc = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,$por_persona);
        $datos[5]['dimension'] = 'NO CORRESPONDE';
        $datos[5]['auto_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=4 target='_blank'>".$total_noc['count']."</a>";
        $datos[5]['auto_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=5 target='_blank'>".$sin_hacer_noc['count']."</a>";
        $datos[5]['auto_pen_porc'] = $sin_hacer_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['auto_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=6 target='_blank'>".$sin_confirmar_noc['count']."</a>";  
        $datos[5]['auto_pen_conf_porc'] = $sin_confirmar_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['eval_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=7 target='_blank'>".$total_noc['count']."</a>";
        $datos[5]['eval_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=8 target='_blank'>".$eval_sin_hacer_noc['count']."</a>";
        $datos[5]['eval_pen_porc'] = $eval_sin_hacer_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['eval_pen_conf'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=9 target='_blank'>".$eval_sin_confirmar_noc['count']."</a>";  
        $datos[5]['eval_pen_conf_porc'] = $eval_sin_confirmar_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['noti_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=10 target='_blank'>".$total_noc['count']."</a>";
        $datos[5]['noti_notificados'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=0&columna=11 target='_blank'>".$eval_notificado_noc['count']."</a>";
        $datos[5]['noti_notificados_porc'] = $eval_notificado_noc['count'] / $total_noc['count'] * 100; 
        
        return $datos;
    }
}