<?php

class ci_autoeval_pendientes_act extends planta_ci {

    protected $s__filtro;

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro) {
        $where = $this->dep('filtro')->get_sql_where();
        if ($where == '1=1')
            return;
        $datos = toba::consulta_php('co_autoevaluaciones')->get_act_pendientes($where);
        $cuadro->set_titulo('Docentes que no completaron o no confirmaron su autoevaluacion');
        $cuadro->set_datos($datos);
    }

    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro(planta_ei_filtro $filtro) {
        if (isset($this->s__filtro)) {
            $filtro->set_datos($this->s__filtro);
        }
    }

    function evt__filtro__filtrar($datos) {
        $this->s__filtro = $datos;
    }

    function evt__filtro__cancelar() {
        unset($this->s__filtro);
    }

}

?>