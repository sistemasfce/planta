<?php
class ci_inicio extends planta_ci
{
    function ini()
    {
        $perfil = toba::usuario()->get_perfiles_funcionales();
        if ($perfil[0] == "docente") {
            toba::vinculador()->navegar_a("planta","280000078");
            return;
        }
    }

    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(planta_ei_formulario $form)
    {
        $mes = date('m');
        $dia = date('d');
        $datos = toba::consulta_php('co_inicio')->get_cumpleanios($mes, $dia);
        if (isset($datos[0])) {
            $texto = 'Hoy es el cumpleaños de ';
            foreach ($datos as $dat) {
                $texto.= $dat['nombre_completo']. ' - ';
            }
            $formulario['titulo'] = $texto;
        } else {
                $formulario['titulo'] = '';
        }
        $form->set_datos($formulario);
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(planta_ei_cuadro $cuadro)
    {
        $anioActual = date('Y');
        $mesActual = date('m');
        $desde = $anioActual.'-'.$mesActual.'-01';
        if ($mesActual == 12) {
            $mesProx = 01;
            $anioActual = 01;
        } else {
            $mesProx = $mesActual+1;    
        }
        $hasta = $anioActual.'-'.$mesProx.'-01';
        $datos_para_cuadro = array();
        $datos = toba::consulta_php('co_inicio')->get_vencimientos($desde, $hasta);
        foreach ($datos as $dat) {
            $fila = $dat;
            if ($dat['estado'] == 1  or $dat['estado'] == 6) {
                $fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
            }
            else if ($dat['estado'] == 3 ) {
                $fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
            }  else {
                $fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
            }
            $datos_para_cuadro[] = $fila;
        }
        $cuadro->set_datos($datos_para_cuadro);        
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro_sin_act --------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_sin_act(planta_ei_cuadro $cuadro)
    {        
        $datos = toba::consulta_php('co_inicio')->get_activos_sin_designacion();
        $cuadro->set_datos($datos);
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro_pv --------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_pv(planta_ei_cuadro $cuadro)
    {
        $anio = date('Y');
        $hasta = $anio.'-02-28';
        $datos = toba::consulta_php('co_inicio')->get_asignaciones_periodo_menor($hasta);
        $datos_para_cuadro = array();
        foreach ($datos as $dat) {
            $fila = $dat;
            if ($dat['estado'] == 1  or $dat['estado'] == 6) {
                $fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
            }
            else if ($dat['estado'] == 3 ) {
                $fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
            }  else {
                $fila['estado_desc'] = '<font color=blue><b>'.$fila['estado_desc'].'</b></font>';
            }
            $datos_para_cuadro[] = $fila;
        }
        $cuadro->set_datos($datos_para_cuadro);        
    }    

    //-----------------------------------------------------------------------------------
    //---- cuadro_horas -----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_horas(planta_ei_cuadro $cuadro)
    {
        $where = '1=1';
        $datos = toba::consulta_php('co_designaciones')->get_horas_neto($where);
        $completo = array();
        foreach ($datos as $dat) {
            $aux = $dat;
            $aux['horas_neto'] = $dat['horas_desig'] - $dat['horas_licenciadas'];
            $aux['horas_utilizadas'] = $dat['horas_asignadas'];
            $aux['horas_a_definir'] = $aux['horas_neto'] - $aux['horas_utilizadas']; 
            if ($aux['horas_a_definir'] > 0)
                $completo[] = $aux;
        } 
        $cuadro->set_datos($completo);        
    }

}
?>
