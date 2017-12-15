<?php
class ci_modificar extends planta_ci
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
        $datos = toba::consulta_php('co_docentes')->get_docentes_completo($where);
        $datos_aux = array();
        
        foreach ($datos as $fila) {
            if ($fila['persona_estado'] == 1 ) {
                $fila['estado'] = '<font color=green><b>'.$fila['estado'].'</b></font>';
            } else {
                $fila['estado'] = '<font color=red><b>'.$fila['estado'].'</b></font>';
            }
            $datos_aux[] = $fila; 
        }
        $cuadro->set_datos($datos_aux);
    }
    
    function evt__cuadro__seleccion($seleccion)
    {
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
            $this->set_pantalla('seleccion');
        }catch (toba_error $e) {
                toba::notificacion()->agregar('No se puede eliminar la autoevaluacion', 'error');
        }
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
        $this->set_pantalla('seleccion');
    }    

}