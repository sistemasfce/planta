<?php
class ci_evaluaciones_por_docente extends planta_ci
{
    protected $s__filtro;
    protected $s__filtro_d;
    
    function conf()
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario_inv' and $perfil[0] != 'usuario') {
            $this->pantalla('pant_inicial')->eliminar_dep('filtro');
        } else {
            $this->pantalla('pant_inicial')->eliminar_dep('filtro_delegado');
        } 
    }
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario_inv' and $perfil[0] != 'usuario') {
            $where = $this->dep('filtro_delegado')->get_sql_where();  
            $datos_filtro = $this->dep('filtro_delegado')->get_datos();
            $ciclo_array = toba::consulta_php('co_parametros')->get_parametro_valor('PAR_AUTOEVAL_CICLO');
            $ciclo = $ciclo_array['valor_num'];
        } else {
            $where = $this->dep('filtro')->get_sql_where();  
            $datos_filtro = $this->dep('filtro')->get_datos();
            $ciclo = $datos_filtro['ciclo_lectivo']['valor'];
        }
        if ($where == '1=1')
                return;
        $persona = $datos_filtro['persona']['valor'];
        $datos = toba::consulta_php('co_evaluaciones')->get_evaluaciones_de_persona_por_ciclo($persona,$ciclo);
        //$cuadro->set_titulo('Evaluaciones del docente');
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
    
    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro_delegado(planta_ei_filtro $filtro)
    {
        if (isset($this->s__filtro_d)) {
            $filtro->set_datos($this->s__filtro_d);
        }
    }

    function evt__filtro_delegado__filtrar($datos)
    {
        $this->s__filtro_d = $datos;
    }

    function evt__filtro_delegado__cancelar()
    {
        unset($this->s__filtro_d);
    }    
    
    
    function get_docentes()
    {
        $documento = toba::usuario()->get_id();
        $persona = toba::consulta_php('co_personas')->get_id($documento);
        $datos = toba::consulta_php('co_asignaciones')->get_docentes_por_sede($persona['persona']);
        return $datos;
    }    
}
?>