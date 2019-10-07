<?php

require_once(toba::proyecto()->get_path_php().'/comunes.php');

class ci_cargar_designacion_edicion extends planta_ci
{
    protected $hay_cambios;

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

    function ini()
    {
        $this->hay_cambios = false;    
    }
	
    //-----------------------------------------------------------------------------------
    //---- cuadro_des ------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_des(planta_ei_cuadro $cuadro)
    {
        $datos_para_cuadro = array();
        $aux = array();
        $datos = $this->tabla('designaciones')->get_filas();
        //$datos = rs_ordenar_por_columna($datos_tabla, 'fecha_desde');

        foreach ($datos as $dat) {
            
            // si es historico no lo muestro
            if ($dat['estado'] != comunes::estado_activo and $dat['estado'] != comunes::estado_vigente) {
                //$fila['estado_desc'] = '<font color=red><b>'.$fila['estado_desc'].'</b></font>';
                continue;
            }  
            
            $fila = $dat;
            $fila['resolucion_desc'] = $dat['resolucion']. '/'.$dat['resolucion_anio']. ' '.$dat['resolucion_tipo_desc'];

            if ($fila['designacion_tipo'] == comunes::desig_alta and $fila['designacion'] != null and ($fila['estado'] == comunes::estado_activo or $fila['estado'] == comunes::estado_con_licencia_p) ) {
                $horas_licenciadas = toba::consulta_php('co_designaciones')->get_horas_licencias_activas($fila['designacion']);
                $fila['carga_horaria_real'] = $fila['carga_horaria_dedicacion'] - $horas_licenciadas['total'];            
            }
            $fila['estado_desc'] = '<font color=green><b>'.$fila['estado_desc'].'</b></font>';
            $datos_para_cuadro[] = $fila;
        }
        $datos_ordenados = rs_ordenar_por_columna($datos_para_cuadro, 'resolucion_fecha');
        $cuadro->set_datos($datos_ordenados);
    }
	
	
    //-----------------------------------------------------------------------------------
    //---- form_des ---------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__form_des__alta($datos)
    {
        // si es contratado, controlar que la categoria sea de profesor
        if ($datos['caracter'] == comunes::car_contratado) {
            // si no es titular, adjunto o asociado mostrar mensaje y no grabar
            if ($datos['categoria'] != comunes::cat_titular and $datos['categoria'] != comunes::cat_asociado and $datos['categoria'] != comunes::cat_adjunto) {
                $this->informar_msg("La designación de caracter contratado sólo puede ser para las categorías titular, adjunto, asociado","error");
                return;
            }
        }
        $datos['nombre_completo'] = '';
        $this->tabla('designaciones')->nueva_fila($datos);
        $this->hay_cambios = true;
    }

    function get_hay_cambios()
    {
        return $this->hay_cambios;
    }
	
}
?>