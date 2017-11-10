<?php
class form_datos_personales extends planta_ei_formulario
{    
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__perfil_docente__procesar = function(es_inicial)
		{
			if (this.ef('perfil_docente').chequeado()) 
				this.ef('legajo').mostrar();
			else 
				this.ef('legajo').ocultar();
			
		}
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__perfil_no_docente__procesar = function(es_inicial)
		{
			if (this.ef('perfil_no_docente').chequeado()) 
				this.ef('legajo_no_doc').mostrar();
			else 
				this.ef('legajo_no_doc').ocultar();
		}
		
		//---- Procesamiento de EFs -----------------------------------
		
		{$this->objeto_js}.evt__apellido__procesar = function()
		{
			this.ef('apellido').set_estado(this.ef('apellido').get_estado().toUpperCase());
		}

		//---- Procesamiento de EFs -----------------------------------
		
		{$this->objeto_js}.evt__nombres__procesar = function()
		{
			this.ef('nombres').set_estado(this.ef('nombres').get_estado().toUpperCase());
		}
			
		";
	}
}
?>