<?php
class ci_modificar_autoevaluacion_edicion extends planta_ci
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
    //---- cuadro_des ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_des(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $datos = $this->tabla('autoevaluaciones_por_act')->get_filas();
        /*
        foreach ($datos as $dat) {
            if ($dat['estado'] != 1)
                continue;            
            $datos_para_cuadro[] = $dat;
        }
        */
        $datos_ordenados = rs_ordenar_por_columna($datos, 'ciclo_lectivo');
        $cuadro->set_datos($datos_ordenados);
        $cuadro->set_titulo('Autoevaluaciones del docente: '.$datos_ordenados[0]['nombre_completo']);

    }
    
    function evt__cuadro_des__seleccion($seleccion)
    {
        $this->tabla('autoevaluaciones_por_act')->set_cursor($seleccion);
    }    
    
    //-----------------------------------------------------------------------------------
    //---- form_des ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_des(planta_ei_formulario $form)
    {
        if ($this->tabla('autoevaluaciones_por_act')->hay_cursor()) {
            $datos = $this->tabla('autoevaluaciones_por_act')->get();    
            $form->set_datos($datos);
        }        
    }

    function evt__form_des__baja()
    {
        $this->tabla('autoevaluaciones_por_act')->set(null);
    }

    function evt__form_des__modificacion($datos)
    {
        $this->tabla('autoevaluaciones_por_act')->set($datos);
        $this->evt__form_des__cancelar();
    }

    function evt__form_des__cancelar()
    {
        $this->tabla('autoevaluaciones_por_act')->resetear_cursor();
    }
}

