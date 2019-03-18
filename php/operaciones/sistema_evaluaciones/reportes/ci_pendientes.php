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
        $total_docencia = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'1');
        $sin_hacer_docencia = toba::consulta_php('co_autoevaluaciones')->get_autoeval_pendientes_por_dimension($ciclo,'1');
        $sin_confirmar_docencia = toba::consulta_php('co_autoevaluaciones')->get_autoeval_no_conf_por_dimension($ciclo,'1'); 
        $datos[0]['dimension'] = 'Docencia';
        $datos[0]['ficha_personas'] = $total_personas['count'];
        $datos[0]['ficha_pen_conf'] = count($pendientes);
        $datos[0]['ficha_pen'] = $sin_ficha;
        $datos[0]['auto_personas'] = $total_docencia['count'];
        $datos[0]['auto_pen'] = $sin_hacer_docencia['count'];
        $datos[0]['auto_pen_conf'] = $sin_confirmar_docencia['count'];        
        
        $total_ext = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'2');
        $sin_hacer_ext = toba::consulta_php('co_autoevaluaciones')->get_autoeval_pendientes_por_dimension($ciclo,'2');
        $sin_confirmar_ext = toba::consulta_php('co_autoevaluaciones')->get_autoeval_no_conf_por_dimension($ciclo,'2');
        $datos[1]['dimension'] = 'Extensión';
        $datos[1]['auto_personas'] = $total_ext['count'];
        $datos[1]['auto_pen'] = $sin_hacer_ext['count'];
        $datos[1]['auto_pen_conf'] = $sin_confirmar_ext['count'];
        
        $total_inv = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'3');
        $sin_hacer_inv = toba::consulta_php('co_autoevaluaciones')->get_autoeval_pendientes_por_dimension($ciclo,'3');
        $sin_confirmar_inv = toba::consulta_php('co_autoevaluaciones')->get_autoeval_no_conf_por_dimension($ciclo,'3');
        $datos[2]['dimension'] = 'Investigación';
        $datos[2]['auto_personas'] = $total_inv['count'];
        $datos[2]['auto_pen'] = $sin_hacer_inv['count'];
        $datos[2]['auto_pen_conf'] = $sin_confirmar_inv['count'];

        $total_gestion = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'4');
        $sin_hacer_gestion = toba::consulta_php('co_autoevaluaciones')->get_autoeval_pendientes_por_dimension($ciclo,'4');
        $sin_confirmar_gestion = toba::consulta_php('co_autoevaluaciones')->get_autoeval_no_conf_por_dimension($ciclo,'4');
        $datos[3]['dimension'] = 'Gestión';
        $datos[3]['auto_personas'] = $total_gestion['count'];
        $datos[3]['auto_pen'] = $sin_hacer_gestion['count'];
        $datos[3]['auto_pen_conf'] = $sin_confirmar_gestion['count'];
        
        $total_for = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'5');
        $sin_hacer_for = toba::consulta_php('co_autoevaluaciones')->get_autoeval_pendientes_por_dimension($ciclo,'5');
        $sin_confirmar_for = toba::consulta_php('co_autoevaluaciones')->get_autoeval_no_conf_por_dimension($ciclo,'5');        
        $datos[4]['dimension'] = 'Formación';
        $datos[4]['auto_personas'] = $total_for['count'];
        $datos[4]['auto_pen'] = $sin_hacer_for['count'];
        $datos[4]['auto_pen_conf'] = $sin_confirmar_for['count'];        
        
        $total_noc = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,'0');
        $sin_hacer_noc = toba::consulta_php('co_autoevaluaciones')->get_autoeval_pendientes_por_dimension($ciclo,'0');
        $sin_confirmar_noc = toba::consulta_php('co_autoevaluaciones')->get_autoeval_no_conf_por_dimension($ciclo,'0');        
        $datos[5]['dimension'] = 'NO CORRESPONDE';
        $datos[5]['auto_personas'] = $total_noc['count'];
        $datos[5]['auto_pen'] = $sin_hacer_noc['count'];
        $datos[5]['auto_pen_conf'] = $sin_confirmar_noc['count'];           
        
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