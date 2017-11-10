<?php
class form_mis_evaluaciones extends planta_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__plan_de_mejora__procesar = function(es_inicial)
		{
			if (this.ef('plan_de_mejora').chequeado()) {
				this.ef('desvio_tit').mostrar();
				this.ef('desvio1').set_obligatorio(true);
				this.ef('desvio1').mostrar();
				this.ef('desvio2').mostrar();
				this.ef('desvio3').mostrar();
				this.ef('act_tit').mostrar();
				this.ef('act1').set_obligatorio(true);
				this.ef('act1').mostrar();
				this.ef('act2').mostrar();
				this.ef('act3').mostrar();
				this.ef('desempenio_tit').mostrar();
				this.ef('desempenio1').set_obligatorio(true); 
				this.ef('desempenio1').mostrar();
				this.ef('desempenio2').mostrar();
				this.ef('desempenio3').mostrar();
			} else {
				this.ef('desvio_tit').ocultar();
				this.ef('desvio1').set_obligatorio(false);
				this.ef('desvio1').ocultar();
				this.ef('desvio2').ocultar();
				this.ef('desvio3').ocultar();
				this.ef('act_tit').ocultar();
				this.ef('act1').set_obligatorio(false);
				this.ef('act1').ocultar();
				this.ef('act2').ocultar();
				this.ef('act3').ocultar();
				this.ef('desempenio_tit').ocultar();
				this.ef('desempenio1').set_obligatorio(false); 
				this.ef('desempenio1').ocultar();
				this.ef('desempenio2').ocultar();
				this.ef('desempenio3').ocultar();
			}
		}

		";
		
			echo "
				{$this->objeto_js}.evt__pdf_plan = function(params) {
				var param = 'plan';
				location.href = vinculador.get_url(null, null, 'vista_jasperreports', {'param': param}); 
				return false;
				}
			";
	}    
}
?>