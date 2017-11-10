<?php
class form_des_mod extends planta_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		{$this->objeto_js}.ini = function(es_inicial)
		{
			this.ef('fecha_desde_jubilacion').ocultar();
		} 
			
		//---- Procesamiento de EFs --------------------------------
		{$this->objeto_js}.evt__designacion_tipo__procesar = function(es_inicial)
		{
			if (this.ef('designacion_tipo').get_estado() == 4) 
				this.ef('fecha_desde_jubilacion').mostrar();
			else
				this.ef('fecha_desde_jubilacion').ocultar();     
		}  
		{$this->objeto_js}.evt__resolucion_tipo__procesar = function(es_inicial)
		{
			if (this.ef('resolucion_tipo').get_estado() == 1) {
				this.ef('ratif_resolucion').mostrar();
				this.ef('ratif_resolucion_fecha').mostrar();
				this.ef('ratif_resolucion_anio').mostrar();   
				this.ef('ratif_resolucion_tipo').mostrar();            
			} else {
				this.ef('ratif_resolucion').ocultar(); 
				this.ef('ratif_resolucion_fecha').ocultar();
				this.ef('ratif_resolucion_anio').ocultar();   
				this.ef('ratif_resolucion_tipo').ocultar();      
			}      
		}               
		
		";
	}    
}
?>