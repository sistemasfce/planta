<?php
class ci_ver_personas_actividades extends planta_ci
{
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $param = toba::memoria()->get_parametros();
        $dimension = $param['dimension'];
        $columna = $param['columna'];
        $ciclo = 2018;
        //ficha: cantidad de personas
        if ($columna == 1) {
           $datos = toba::consulta_php('co_autoevaluaciones')->get_cantidad_fichas($ciclo,0);        
        }
        //ficha: no subieron 
        if ($columna == 2) {
            $datos = array();
        }  
        //ficha: no confirmaron
        if ($columna == 3) {   
           $where = '1=1';
           $datos = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo);
        }     
        //autoeval: cantidad de personas
        if ($columna == 4) {
            $datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0);
        } 
        //autoeval: no se evaluaron
        if ($columna == 5) {    
            $datos = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',0,0);
        }    
        //autoeval: no confirmaron
        if ($columna == 6) {       
            $datos = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',0,0); 
        } 
        //eval: cantidad
        if ($columna == 7) {        
            $datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0);
        }
        //eval: no fue evaluada
        if ($columna == 8) {    
            $datos = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',0,0);
        }   
        //eval: no confirmada
        if ($columna == 9) {    
            $datos = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',0,0);             
        }   
        //noti: cant de personas
        if ($columna == 10) { 
            $datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0);
        }    
        //noti: no se notificaron
        if ($columna == 11) {        
            $datos = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,0,0);
        }
        $cuadro->set_datos($datos); 
    }    
}