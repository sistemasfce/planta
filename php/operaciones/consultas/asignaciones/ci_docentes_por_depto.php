<?php
class ci_docentes_por_depto extends planta_ci
{
    protected $s__filtro;

    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__pant_inicial(toba_ei_pantalla $pantalla)
    {
        //toba::memoria()->set_dato('persona',null);
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario') {
            $documento = toba::usuario()->get_id();
            $this->pantalla('pant_inicial')->eliminar_dep('filtro');
            $persona = toba::consulta_php('co_personas')->get_id($documento);
            toba::memoria()->set_dato('persona',$persona['persona']);
        }       
    }
    
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] != 'admin' and $perfil[0] != 'usuario') {
            // si la persona es docente, dado su id busco su cargo y ubicacion
            $persona = toba::memoria()->get_dato('persona');
            $datos = toba::consulta_php('co_asignaciones')->get_docentes_por_depto_persona($persona);
            $cuadro->set_datos($datos);           
        } else {
            // si la persona es admin uso el filtro
            $filtro = $this->dep('filtro')->get_sql_where();
            if ($filtro != '1=1') {
                $datos = toba::consulta_php('co_asignaciones')->get_docentes_por_depto($filtro);
                $cuadro->set_datos($datos);
            }          
        }               
        

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
?>