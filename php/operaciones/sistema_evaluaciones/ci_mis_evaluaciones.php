<?php
class ci_mis_evaluaciones extends planta_ci
{
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
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $persona = toba::memoria()->get_dato('persona');
        $nombre = toba::memoria()->get_dato('nombre');
        $cuadro->set_titulo('Docente: '.$nombre);
        $ciclo = toba::memoria()->get_dato('ciclo');
        $datos = toba::consulta_php('co_evaluaciones')->get_evaluaciones_de_persona_por_ciclo($persona, $ciclo);
        //$this->tabla('evaluaciones')->cargar(array('persona'=>$persona));

        $aux = array();
        foreach ($datos as $i) {           
            if ($i['eval_confirmado'] == 'S') {
                    $i['imagen'] = toba_recurso::imagen_toba('tilde.gif', true);
            } else {
                    $i['imagen'] = toba_recurso::imagen_toba('vacio.png', true);
            }
            if ($i['eval_notificacion'] == 'S') {
                    $i['imagen_not'] = toba_recurso::imagen_toba('tilde.gif', true);
            } else {
                    $i['imagen_not'] = toba_recurso::imagen_toba('vacio.png', true);
            }            
            $aux[] = $i;
        }
        $cuadro->set_datos($aux);        
    }

    function evt__cuadro__seleccion($seleccion)
    {
        toba::memoria()->set_dato('asignacion',$seleccion['asignacion']);
        if ($seleccion['eval_confirmado'] == 'S') {
            $this->set_pantalla('pant_form');
        } else {
            // SOLO PARA PROBAR!!!
            $this->set_pantalla('pant_form');
            //$this->informar_msg("La actividad NO fue evaluada","info");
        }       
    }

    function evt__cuadro__volver($datos)
    {
        $this->relacion()->resetear();
        $this->controlador->set_pantalla('pant_desempenio');
    }
    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(planta_ei_formulario $form)
    {
            $asignacion = toba::memoria()->get_dato('asignacion');  
            $datos_tabla = toba::consulta_php('co_evaluaciones')->get_evaluacion_tabla($asignacion);
            $datos = toba::consulta_php('co_evaluaciones')->get_evaluacion($asignacion);

            $this->relacion()->tabla('asignaciones')->resetear();
            $this->relacion()->tabla('asignaciones')->cargar($datos_tabla);

            if ($datos['eval_confirmado'] == 'S') {
                    $form->set_datos($datos);
            } else {
                    $aux['actividad_desc'] = 'ESTA ACTIVIDAD NO FUE EVALUADA';
                    $form->set_datos($aux); 
                    $form->evento('modificacion')->desactivar();
                    $form->evento('pdf_plan')->desactivar();
            }
            if ($datos['eval_notificacion'] == 'S')
                    $form->evento('modificacion')->desactivar();       
    }

    function evt__form__modificacion($datos)
    {     
            $datos['eval_notificacion_fecha'] = date('Y-m-d');
            $asignacion = toba::memoria()->get_dato('asignacion');  
            $datos['asignacion'] = $asignacion;
            $this->tabla('asignaciones')->set($datos);
            $this->relacion()->sincronizar();
            $this->relacion()->resetear();
            toba::memoria()->set_dato('asignacion',null);    
            $this->set_pantalla('pant_inicial');
    }

    function evt__form__cancelar()
    {
            $this->relacion()->resetear();
            $this->set_pantalla('pant_inicial');
    }

}
?>