<?php
class ci_modificar_evaluacion_edicion extends planta_ci
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
        $datos = $this->tabla('evaluaciones')->get_filas();
        foreach ($datos as $dat) {
            if ($dat['estado'] != 1)
                continue;            
            $datos_para_cuadro[] = $dat;
        }
        $cuadro->set_datos($datos_para_cuadro);
    }
    
    function evt__cuadro_des__seleccion($seleccion)
    {
        $this->tabla('evaluaciones')->set_cursor($seleccion);
    }    
    
    //-----------------------------------------------------------------------------------
    //---- form_des ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_des(planta_ei_formulario $form)
    {
        if ($this->tabla('evaluaciones')->hay_cursor()) {
            $datos = $this->tabla('evaluaciones')->get();    
            $form->set_datos($datos);
        }        
    }

    function evt__form_des__baja()
    {
        $this->tabla('evaluaciones')->set(null);
    }

    function evt__form_des__modificacion($datos)
    {
        $this->tabla('evaluaciones')->set($datos);
        $this->evt__form_des__cancelar();
    }

    function evt__form_des__cancelar()
    {
        $this->tabla('evaluaciones')->resetear_cursor();
    }
}
