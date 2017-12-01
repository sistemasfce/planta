<?php
class cuadro_evaluado extends planta_ei_cuadro
{
    /**
    * Atrapa el evento seleccion del cuadro e invoca manualmente el serviccio vista_jasperreports pasandole el hash por parámetro
    */
    function extender_objeto_js()
    {
            echo "
                {$this->objeto_js}.evt__listado = function(params) {
                var param = 'evaluado';
                location.href = vinculador.get_url(null, null, 'vista_jasperreports', {'param': param});
                    return false;
                }
            ";
        
            echo "
                {$this->objeto_js}.evt__reporte = function(params) {
                var param = 'evaluado_final';
                location.href = vinculador.get_url(null, null, 'vista_jasperreports', {'param': param});
                    return false;
                }
            ";
    }    
}
?>