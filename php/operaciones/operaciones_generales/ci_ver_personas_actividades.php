<?php
class ci_ver_personas_actividades extends planta_ci
{
    protected $s__datos;
    
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $param = toba::memoria()->get_parametros();
        $dimension = $param['dimension'];
        $columna = $param['columna'];
        $departamento = 0;
        $ubicacion = 0;
        
        if(sizeof($param)>2){
            $departamento = $param['departamento'];
            $ubicacion = $param['ubicacion'];
        }
        $parametro_ciclo = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
        $ciclo =  $parametro_ciclo['valor_num'];
        //ficha: cantidad de personas


        if ($columna == 1) {
           $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_cantidad_fichas($ciclo,0,$departamento,$ubicacion,$dimension);    

        }
        //ficha: no subieron 
        if ($columna == 2) {
            $datos = array();
            $where = "(ficha_docente_path is null OR ficha_docente_path = '')";
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo,$departamento,$ubicacion,$dimension);
        }  
        //ficha: no confirmaron
        if ($columna == 3) {   
           $where = '1=1';
           $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_ficha_pendientes($where,$ciclo,$departamento,$ubicacion,$dimension);
        }        
     
         
        //autoeval: cantidad de personas
        if ($columna == 4) {
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0,$departamento,$ubicacion);
        } 
        //autoeval: no se evaluaron
        if ($columna == 5) {    
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'autoeval',0,0,$departamento,$ubicacion);
        }    
        //autoeval: no confirmaron
        if ($columna == 6) {       
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'autoeval',0,0,$departamento,$ubicacion); 
        } 
        //eval: cantidad
        if ($columna == 7) {        
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0,$departamento,$ubicacion);
        }
        //eval: no fue evaluada
        if ($columna == 8) {    
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_pendientes_por_dimension($ciclo,$dimension,'eval',0,0,$departamento,$ubicacion);
        }   
        //eval: no confirmada
        if ($columna == 9) {    
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_no_conf_por_dimension($ciclo,$dimension,'eval',0,0,$departamento,$ubicacion);             
        }   
        //noti: cant de personas
        if ($columna == 10) { 
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_personas_por_dimension($ciclo,$dimension,0,0,$departamento,$ubicacion);
        }    
        //noti: no se notificaron
        if ($columna == 11) {        
            $this->s__datos = toba::consulta_php('co_autoevaluaciones')->get_notifico_por_dimension($ciclo,$dimension,0,0,$departamento,$ubicacion);
        }
        $cuadro->set_datos($this->s__datos); 
    }    
}