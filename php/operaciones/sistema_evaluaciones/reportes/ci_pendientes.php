<?php
class ci_pendientes extends planta_ci
{
    protected $s__filtro;

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_dim(planta_ei_cuadro $cuadro)
    {
        $where = '1=1';
        $sin_ficha=0;
        $ciclo = '2018';
        $pendientes = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo);
        foreach ($pendientes as $pen) {
            if ($pen['confirmado'] == '')
                $sin_ficha+=1;
        }        
        $total_personas = toba::consulta_php('co_autoevaluaciones')->get_cantidad_fichas($ciclo);
        
        $total_docencia = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'1','1');
        $sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'1','autoeval','1');
        $sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'1','autoeval','1'); 
        $eval_sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'1','eval','1');
        $eval_sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'1','eval','1');        
        $eval_notificado = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'1','1');
        $datos[0]['dimension'] = 'Docencia';
        $datos[0]['ficha_personas'] = $total_personas['count'];
        $datos[0]['ficha_pen'] = $sin_ficha; // .' - '.$sin_ficha / $total_personas['count'] * 100;
        $datos[0]['ficha_pen_porc'] = $sin_ficha / $total_personas['count'] * 100;      
        $datos[0]['ficha_pen_conf'] = count($pendientes);
        $datos[0]['ficha_pen_conf_porc'] = count($pendientes) / $total_personas['count'] * 100; 
        
        $datos[0]['auto_personas'] = $total_docencia['count'];
        $datos[0]['auto_pen'] = $sin_hacer_docencia['count'];
        $datos[0]['auto_pen_porc'] = $sin_hacer_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['auto_pen_conf'] = $sin_confirmar_docencia['count']; 
        $datos[0]['auto_pen_conf_porc'] = $sin_confirmar_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['eval_personas'] = $total_docencia['count'];
        $datos[0]['eval_pen'] = $eval_sin_hacer_docencia['count'];
        $datos[0]['eval_pen_porc'] = $eval_sin_hacer_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['eval_pen_conf'] = $eval_sin_confirmar_docencia['count'];   
        $datos[0]['eval_pen_conf_porc'] = $eval_sin_confirmar_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['noti_personas'] = $total_docencia['count'];
        $datos[0]['noti_notificados'] = $eval_notificado['count'];
        $datos[0]['noti_notificados_porc'] = $eval_notificado['count'] / $total_docencia['count'] * 100;
        
        $total_ext = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'2','1');
        $sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'2','autoeval','1');
        $sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'2','autoeval','1');
        $eval_sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'2','eval','1');
        $eval_sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'2','eval','1');        
        $eval_notificado_ext = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'2','1');
        $datos[1]['dimension'] = 'Extensión';
        $datos[1]['auto_personas'] = $total_ext['count'];
        $datos[1]['auto_pen'] = $sin_hacer_ext['count'];
        $datos[1]['auto_pen_porc'] = $sin_hacer_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['auto_pen_conf'] = $sin_confirmar_ext['count'];
        $datos[1]['auto_pen_conf_porc'] = $sin_confirmar_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['eval_personas'] = $total_ext['count'];
        $datos[1]['eval_pen'] = $eval_sin_hacer_ext['count'];
        $datos[1]['eval_pen_porc'] = $sin_hacer_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['eval_pen_conf'] = $eval_sin_confirmar_ext['count'];
        $datos[1]['eval_pen_conf_porc'] = $eval_sin_confirmar_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['noti_personas'] = $total_ext['count'];
        $datos[1]['noti_notificados'] = $eval_notificado_ext['count'];
        $datos[1]['noti_notificados_porc'] = $eval_notificado_ext['count'] / $total_ext['count'] * 100;       
        
        $total_inv = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'3','1');
        $sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'3','autoeval','1');
        $sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'3','autoeval','1');
        $eval_sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'3','eval','1');
        $eval_sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'3','eval','1');
        $eval_notificado_inv = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'3','1');
        $datos[2]['dimension'] = 'Investigación';
        $datos[2]['auto_personas'] = $total_inv['count'];
        $datos[2]['auto_pen'] = $sin_hacer_inv['count'];
        $datos[2]['auto_pen_porc'] = $sin_hacer_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['auto_pen_conf'] = $sin_confirmar_inv['count'];
        $datos[2]['auto_pen_conf_porc'] = $sin_confirmar_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['eval_personas'] = $total_inv['count'];
        $datos[2]['eval_pen'] = $eval_sin_hacer_inv['count'];
        $datos[2]['eval_pen_porc'] = $eval_sin_hacer_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['eval_pen_conf'] = $eval_sin_confirmar_inv['count'];
        $datos[2]['eval_pen_conf_porc'] = $sin_confirmar_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['noti_personas'] = $total_inv['count'];
        $datos[2]['noti_notificados'] = $eval_notificado_inv['count'];
        $datos[2]['noti_notificados_porc'] = $eval_notificado_inv['count'] / $total_inv['count'] * 100;
        
        $total_gestion = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'4','1');
        $sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'4','autoeval','1');
        $sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'4','autoeval','1');
        $eval_sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'4','eval','1');
        $eval_sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'4','eval','1');
        $eval_notificado_gestion = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'4','1');
        $datos[3]['dimension'] = 'Gestión';
        $datos[3]['auto_personas'] = $total_gestion['count'];
        $datos[3]['auto_pen'] = $sin_hacer_gestion['count'];
        $datos[3]['auto_pen_porc'] = $sin_hacer_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['auto_pen_conf'] = $sin_confirmar_gestion['count'];
        $datos[3]['auto_pen_conf_porc'] = $sin_confirmar_gestion['count'] / $total_gestion['count'] * 100;        
        $datos[3]['eval_personas'] = $total_gestion['count'];
        $datos[3]['eval_pen'] = $eval_sin_hacer_gestion['count'];
        $datos[3]['eval_pen_porc'] = $eval_sin_hacer_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['eval_pen_conf'] = $eval_sin_confirmar_gestion['count'];    
        $datos[3]['eval_pen_conf_porc'] = $eval_sin_confirmar_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['noti_personas'] = $total_gestion['count'];
        $datos[3]['noti_notificados'] = $eval_notificado_gestion['count'];
        $datos[3]['noti_notificados_porc'] = $eval_notificado_gestion['count'] / $total_gestion['count'] * 100;
        
        $total_for = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'5','1');
        $sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'5','autoeval','1');
        $sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'5','autoeval','1');       
        $eval_sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'5','eval','1');
        $eval_sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'5','eval','1');                
        $eval_notificado_for = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'5','1');
        $datos[4]['dimension'] = 'Formación';
        $datos[4]['auto_personas'] = $total_for['count'];
        $datos[4]['auto_pen'] = $sin_hacer_for['count'];
        $datos[4]['auto_pen_porc'] = $sin_hacer_for['count'] / $total_for['count'] * 100;
        $datos[4]['auto_pen_conf'] = $sin_confirmar_for['count'];  
        $datos[4]['auto_pen_conf_porc'] = $sin_confirmar_for['count'] / $total_for['count'] * 100;
        $datos[4]['eval_personas'] = $total_for['count'];
        $datos[4]['eval_pen'] = $eval_sin_hacer_for['count'];
        $datos[4]['eval_pen_porc'] = $eval_sin_hacer_for['count'] / $total_for['count'] * 100;
        $datos[4]['eval_pen_conf'] = $eval_sin_confirmar_for['count'];  
        $datos[4]['eval_pen_conf_porc'] = $eval_sin_confirmar_for['count'] / $total_for['count'] * 100;        
        $datos[4]['noti_personas'] = $total_for['count'];
        $datos[4]['noti_notificados'] = $eval_notificado_for['count'];
        $datos[4]['noti_notificados_porc'] = $eval_notificado_for['count'] / $total_for['count'] * 100;
        
        $total_noc = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'0','1');
        $sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'0','autoeval','1');
        $sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'0','autoeval','1');   
        $eval_sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'0','eval','1');
        $eval_sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'0','eval','1');        
        $eval_notificado_noc = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'0','1');
        $datos[5]['dimension'] = 'NO CORRESPONDE';
        $datos[5]['auto_personas'] = $total_noc['count'];
        $datos[5]['auto_pen'] = $sin_hacer_noc['count'];
        $datos[5]['auto_pen_porc'] = $sin_hacer_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['auto_pen_conf'] = $sin_confirmar_noc['count'];   
        $datos[5]['auto_pen_conf_porc'] = $sin_confirmar_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['eval_personas'] = $total_noc['count'];
        $datos[5]['eval_pen'] = $eval_sin_hacer_noc['count'];
        $datos[5]['eval_pen_porc'] = $eval_sin_hacer_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['eval_pen_conf'] = $eval_sin_confirmar_noc['count'];    
        $datos[5]['eval_pen_conf_porc'] = $eval_sin_confirmar_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['noti_personas'] = $total_noc['count'];
        $datos[5]['noti_notificados'] = $eval_notificado_noc['count'];
        $datos[5]['noti_notificados_porc'] = $eval_notificado_noc['count'] / $total_noc['count'] * 100;        
        //$cuadro->set_titulo('Docentes que no subieron o no confirmaron ficha docente');
        $cuadro->set_datos($datos);        
    }        

    function conf__cuadro_dim_act(planta_ei_cuadro $cuadro)
    {
        $where = '1=1';
        $sin_ficha=0;
        $ciclo = '2018';
        $path = toba::proyecto()->get_www();
       
        $pendientes = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo);
        foreach ($pendientes as $pen) {
            if ($pen['confirmado'] == '')
                $sin_ficha+=1;
        }        
        $total_personas = toba::consulta_php('co_autoevaluaciones')->get_cantidad_fichas($ciclo);
        
        $total_docencia = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'1','0');
        $sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'1','autoeval','0');
        $sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'1','autoeval','0'); 
        $eval_sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'1','eval','0');
        $eval_sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'1','eval','0');        
        $eval_notificado = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'1','0');
        $datos[0]['dimension'] = 'Docencia';
        $datos[0]['ficha_personas'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=1 target='_blank'>".$total_personas['count']."</a>"; 
        $datos[0]['ficha_pen'] = "<a href=".$path['url']."/?ai=planta||280000226&tcm=previsualizacion&tm=1&dimension=1&columna=2 target='_blank'>".$sin_ficha."</a>"; 
        $datos[0]['ficha_pen_porc'] = $sin_ficha / $total_personas['count'] * 100;      
        $datos[0]['ficha_pen_conf'] = count($pendientes);
        $datos[0]['ficha_pen_conf_porc'] = count($pendientes) / $total_personas['count'] * 100; 
        
        $datos[0]['auto_personas'] = $total_docencia['count'];
        $datos[0]['auto_pen'] = $sin_hacer_docencia['count'];
        $datos[0]['auto_pen_porc'] = $sin_hacer_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['auto_pen_conf'] = $sin_confirmar_docencia['count']; 
        $datos[0]['auto_pen_conf_porc'] = $sin_confirmar_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['eval_personas'] = $total_docencia['count'];
        $datos[0]['eval_pen'] = $eval_sin_hacer_docencia['count'];
        $datos[0]['eval_pen_porc'] = $eval_sin_hacer_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['eval_pen_conf'] = $eval_sin_confirmar_docencia['count'];   
        $datos[0]['eval_pen_conf_porc'] = $eval_sin_confirmar_docencia['count'] / $total_docencia['count'] * 100;
        $datos[0]['noti_personas'] = $total_docencia['count'];
        $datos[0]['noti_notificados'] = $eval_notificado['count'];
        $datos[0]['noti_notificados_porc'] = $eval_notificado['count'] / $total_docencia['count'] * 100;
        
        $total_ext = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'2','0');
        $sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'2','autoeval','0');
        $sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'2','autoeval','0');
        $eval_sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'2','eval','0');
        $eval_sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'2','eval','0');        
        $eval_notificado_ext = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'2','0');
        $datos[1]['dimension'] = 'Extensión';
        $datos[1]['auto_personas'] = $total_ext['count'];
        $datos[1]['auto_pen'] = $sin_hacer_ext['count'];
        $datos[1]['auto_pen_porc'] = $sin_hacer_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['auto_pen_conf'] = $sin_confirmar_ext['count'];
        $datos[1]['auto_pen_conf_porc'] = $sin_confirmar_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['eval_personas'] = $total_ext['count'];
        $datos[1]['eval_pen'] = $eval_sin_hacer_ext['count'];
        $datos[1]['eval_pen_porc'] = $sin_hacer_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['eval_pen_conf'] = $eval_sin_confirmar_ext['count'];
        $datos[1]['eval_pen_conf_porc'] = $eval_sin_confirmar_ext['count'] / $total_ext['count'] * 100;
        $datos[1]['noti_personas'] = $total_ext['count'];
        $datos[1]['noti_notificados'] = $eval_notificado_ext['count'];
        $datos[1]['noti_notificados_porc'] = $eval_notificado_ext['count'] / $total_ext['count'] * 100;       
        
        $total_inv = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'3','0');
        $sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'3','autoeval','0');
        $sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'3','autoeval','0');
        $eval_sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'3','eval','0');
        $eval_sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'3','eval','0');
        $eval_notificado_inv = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'3','0');
        $datos[2]['dimension'] = 'Investigación';
        $datos[2]['auto_personas'] = $total_inv['count'];
        $datos[2]['auto_pen'] = $sin_hacer_inv['count'];
        $datos[2]['auto_pen_porc'] = $sin_hacer_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['auto_pen_conf'] = $sin_confirmar_inv['count'];
        $datos[2]['auto_pen_conf_porc'] = $sin_confirmar_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['eval_personas'] = $total_inv['count'];
        $datos[2]['eval_pen'] = $eval_sin_hacer_inv['count'];
        $datos[2]['eval_pen_porc'] = $eval_sin_hacer_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['eval_pen_conf'] = $eval_sin_confirmar_inv['count'];
        $datos[2]['eval_pen_conf_porc'] = $sin_confirmar_inv['count'] / $total_inv['count'] * 100;
        $datos[2]['noti_personas'] = $total_inv['count'];
        $datos[2]['noti_notificados'] = $eval_notificado_inv['count'];
        $datos[2]['noti_notificados_porc'] = $eval_notificado_inv['count'] / $total_inv['count'] * 100;
        
        $total_gestion = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'4','0');
        $sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'4','autoeval','0');
        $sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'4','autoeval','0');
        $eval_sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'4','eval','0');
        $eval_sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'4','eval','0');
        $eval_notificado_gestion = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'4','0');
        $datos[3]['dimension'] = 'Gestión';
        $datos[3]['auto_personas'] = $total_gestion['count'];
        $datos[3]['auto_pen'] = $sin_hacer_gestion['count'];
        $datos[3]['auto_pen_porc'] = $sin_hacer_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['auto_pen_conf'] = $sin_confirmar_gestion['count'];
        $datos[3]['auto_pen_conf_porc'] = $sin_confirmar_gestion['count'] / $total_gestion['count'] * 100;        
        $datos[3]['eval_personas'] = $total_gestion['count'];
        $datos[3]['eval_pen'] = $eval_sin_hacer_gestion['count'];
        $datos[3]['eval_pen_porc'] = $eval_sin_hacer_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['eval_pen_conf'] = $eval_sin_confirmar_gestion['count'];    
        $datos[3]['eval_pen_conf_porc'] = $eval_sin_confirmar_gestion['count'] / $total_gestion['count'] * 100;
        $datos[3]['noti_personas'] = $total_gestion['count'];
        $datos[3]['noti_notificados'] = $eval_notificado_gestion['count'];
        $datos[3]['noti_notificados_porc'] = $eval_notificado_gestion['count'] / $total_gestion['count'] * 100;
        
        $total_for = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'5','0');
        $sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'5','autoeval','0');
        $sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'5','autoeval','0');       
        $eval_sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'5','eval','0');
        $eval_sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'5','eval','0');                
        $eval_notificado_for = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'5','0');
        $datos[4]['dimension'] = 'Formación';
        $datos[4]['auto_personas'] = $total_for['count'];
        $datos[4]['auto_pen'] = $sin_hacer_for['count'];
        $datos[4]['auto_pen_porc'] = $sin_hacer_for['count'] / $total_for['count'] * 100;
        $datos[4]['auto_pen_conf'] = $sin_confirmar_for['count'];  
        $datos[4]['auto_pen_conf_porc'] = $sin_confirmar_for['count'] / $total_for['count'] * 100;
        $datos[4]['eval_personas'] = $total_for['count'];
        $datos[4]['eval_pen'] = $eval_sin_hacer_for['count'];
        $datos[4]['eval_pen_porc'] = $eval_sin_hacer_for['count'] / $total_for['count'] * 100;
        $datos[4]['eval_pen_conf'] = $eval_sin_confirmar_for['count'];  
        $datos[4]['eval_pen_conf_porc'] = $eval_sin_confirmar_for['count'] / $total_for['count'] * 100;        
        $datos[4]['noti_personas'] = $total_for['count'];
        $datos[4]['noti_notificados'] = $eval_notificado_for['count'];
        $datos[4]['noti_notificados_porc'] = $eval_notificado_for['count'] / $total_for['count'] * 100;
        
        $total_noc = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'0','0');
        $sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'0','autoeval','0');
        $sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'0','autoeval','0');   
        $eval_sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,'0','eval','0');
        $eval_sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,'0','eval','0');        
        $eval_notificado_noc = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,'0','0');
        $datos[5]['dimension'] = 'NO CORRESPONDE';
        $datos[5]['auto_personas'] = $total_noc['count'];
        $datos[5]['auto_pen'] = $sin_hacer_noc['count'];
        $datos[5]['auto_pen_porc'] = $sin_hacer_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['auto_pen_conf'] = $sin_confirmar_noc['count'];   
        $datos[5]['auto_pen_conf_porc'] = $sin_confirmar_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['eval_personas'] = $total_noc['count'];
        $datos[5]['eval_pen'] = $eval_sin_hacer_noc['count'];
        $datos[5]['eval_pen_porc'] = $eval_sin_hacer_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['eval_pen_conf'] = $eval_sin_confirmar_noc['count'];    
        $datos[5]['eval_pen_conf_porc'] = $eval_sin_confirmar_noc['count'] / $total_noc['count'] * 100;
        $datos[5]['noti_personas'] = $total_noc['count'];
        $datos[5]['noti_notificados'] = $eval_notificado_noc['count'];
        $datos[5]['noti_notificados_porc'] = $eval_notificado_noc['count'] / $total_noc['count'] * 100;        
        //$cuadro->set_titulo('Docentes que no subieron o no confirmaron ficha docente');
        $cuadro->set_datos($datos);
    }
    
    function conf__cuadro_depto(planta_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();  
        if ($where == '1=1')
                return;
        $datos[0]['sede'] = 'TW';
        $datos[0]['depto'] = 'CONTABLE';
        $datos[1]['sede'] = 'TW';
        $datos[1]['depto'] = 'DERECHO';
        $datos[2]['sede'] = 'TW';
        $datos[2]['depto'] = 'ADMINISTRACION';
        $datos[3]['sede'] = 'TW';
        $datos[3]['depto'] = 'ECONOMIA';
        $datos[4]['sede'] = 'TW';
        $datos[4]['depto'] = 'MATEMATICA';
        $datos[5]['sede'] = 'TW';
        $datos[5]['depto'] = 'HUMANIDADES';
        $datos[6]['sede'] = 'TW';
        $datos[6]['depto'] = 'SIN DEPTO';        
        $datos[7]['sede'] = 'CR';
        $datos[7]['depto'] = 'CONTABLE';
        $datos[8]['sede'] = 'CR';
        $datos[8]['depto'] = 'DERECHO';
        $datos[9]['sede'] = 'CR';
        $datos[9]['depto'] = 'ADMINISTRACION';
        $datos[10]['sede'] = 'CR';
        $datos[10]['depto'] = 'ECONOMIA';
        $datos[11]['sede'] = 'CR';
        $datos[11]['depto'] = 'MATEMATICA';
        $datos[12]['sede'] = 'CR';
        $datos[12]['depto'] = 'HUMANIDADES';
        $datos[13]['sede'] = 'CR';
        $datos[13]['depto'] = 'SIN DEPTO';        
        $datos[14]['sede'] = 'ES';
        $datos[14]['depto'] = 'CONTABLE';
        $datos[15]['sede'] = 'ES';
        $datos[15]['depto'] = 'DERECHO';
        $datos[16]['sede'] = 'ES';
        $datos[16]['depto'] = 'ADMINISTRACION';
        $datos[17]['sede'] = 'ES';
        $datos[17]['depto'] = 'ECONOMIA';
        $datos[18]['sede'] = 'ES';
        $datos[18]['depto'] = 'MATEMATICA';
        $datos[19]['sede'] = 'ES';
        $datos[19]['depto'] = 'HUMANIDADES';
        $datos[20]['sede'] = 'ES';
        $datos[20]['depto'] = 'SIN DEPTO'; 
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
}