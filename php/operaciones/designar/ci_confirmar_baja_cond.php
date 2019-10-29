<?php

class ci_confirmar_baja_cond extends planta_ci
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
        $where = $this->dep('filtro')->get_sql_where();
        $datos = toba::consulta_php('co_docentes')->get_bajas_cond_sin_confirmar($where);
        $cuadro->set_datos($datos);
    }

    function evt__cuadro__seleccion($seleccion)
    {
        toba::memoria()->set_dato('persona',$seleccion);
        $this->relacion()->cargar($seleccion);
        $this->set_pantalla('edicion');
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
    //---- Eventos ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__procesar()
    {
        try {
            $this->dep('relacion')->sincronizar();
            $this->dep('relacion')->resetear();
            $persona = toba::memoria()->get_dato('persona');
            toba::consulta_php('act_designaciones')->cambiar_estado_persona($persona['persona'], comunes::estado_historico);
            toba::consulta_php('act_asignaciones')->cambiar_estado_persona($persona['persona'], comunes::estado_historico);
            toba::consulta_php('act_docentes')->inactivar_docente($persona['persona']);
            $this->informar_msg("La baja condicionada se confirmó correctamente","info");
            $this->set_pantalla('seleccion');
        }catch (toba_error $e) {
            toba::notificacion()->agregar('No se puede confirmar la baja de designacion'.$e, 'error');
        }
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
        $this->set_pantalla('seleccion');
    }    

    //-----------------------------------------------------------------------------------
    //---- JAVASCRIPT -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function extender_objeto_js()
    {   
        $msj_confirmacion = 'Se han detectado cambios ¿Desea salir sin guardar?';
        if ($this->dependencia('ci_edicion')->get_hay_cambios()) {
            $confirmar = 'var confirmar = true;';
        } else {
            $confirmar = 'var confirmar = hay_algun_cambio || toba.hay_cambios();';
        }   
        
        echo $this->js_evt_cancelar();
        echo "
            var hay_algun_cambio = false;

            function confirmar_cambios(proyecto, operacion, url, es_popup) {
                var resp = true;
                if (! es_popup || typeof(es_popup) == 'undefined') {
                    $confirmar
                    if (confirmar) {
                        resp = confirm('$msj_confirmacion');
                    }
                }
                hay_algun_cambio = false;
                return resp;
            }          
            toba.set_callback_menu(confirmar_cambios);
        ";
    }      

    // Se modulariza el evento js cancelar para poder personalizar 
    // su comportamiento al extender esta clase
    function js_evt_cancelar()
    {
        return "
            {$this->objeto_js}.evt__cancelar = function() {
                return confirmar_cambios();
            }
        ";
    }   
}
?>