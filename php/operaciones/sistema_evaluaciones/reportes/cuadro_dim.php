<?php

class cuadro_dim extends planta_ei_cuadro
{

    function vista_excel(toba_vista_excel $salida )
    {
        //$this->salida = $salida;
        $titulo = $this->get_titulo();
        $cant_columnas = count($this->_columnas);
        if ($titulo != '') {
            $salida->set_hoja_nombre($titulo);
            $salida->titulo($titulo, $cant_columnas);
        }
        if ($this->_info_cuadro["subtitulo"] != '') {
            $salida->titulo($this->_info_cuadro["subtitulo"], $cant_columnas);
        }
        $this->generar_salida("excel", $salida);
    }
}