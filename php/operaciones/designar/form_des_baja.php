<?php
class form_des_baja extends planta_ei_formulario
{
    //-----------------------------------------------------------------------------------
    //---- JAVASCRIPT -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function extender_objeto_js()
    {
        echo "
        {$this->objeto_js}.ini = function(es_inicial)
        {
            this.ef('ubicacion').ocultar();
        } 

        //---- Procesamiento de EFs --------------------------------
        {$this->objeto_js}.evt__designacion_tipo__procesar = function(es_inicial)
        {
            if (this.ef('designacion_tipo').get_estado() == 4) 
                this.ef('fecha_desde_jubilacion').mostrar();
            else
                this.ef('fecha_desde_jubilacion').ocultar();     
        }            
        ";
    }
}
?>