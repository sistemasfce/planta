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
		
		{$this->objeto_js}.evt__eval_plan_de_mejora__procesar = function(es_inicial)
		{
			if (this.ef('eval_plan_de_mejora').chequeado()) {
				this.ef('desvio_tit').mostrar();
				this.ef('eval_desvio1').set_obligatorio(true);
				this.ef('eval_desvio1').mostrar();
				this.ef('eval_desvio2').mostrar();
				this.ef('eval_desvio3').mostrar();
				this.ef('act_tit').mostrar();
				this.ef('eval_act1').set_obligatorio(true);
				this.ef('eval_act1').mostrar();
				this.ef('eval_act2').mostrar();
				this.ef('eval_act3').mostrar();
				this.ef('desempenio_tit').mostrar();
				this.ef('eval_desempenio1').set_obligatorio(true); 
				this.ef('eval_desempenio1').mostrar();
				this.ef('eval_desempenio2').mostrar();
				this.ef('eval_desempenio3').mostrar();
			} else {
				this.ef('desvio_tit').ocultar();
				this.ef('eval_desvio1').set_obligatorio(false);
				this.ef('eval_desvio1').ocultar();
				this.ef('eval_desvio2').ocultar();
				this.ef('eval_desvio3').ocultar();
				this.ef('act_tit').ocultar();
				this.ef('eval_act1').set_obligatorio(false);
				this.ef('eval_act1').ocultar();
				this.ef('eval_act2').ocultar();
				this.ef('eval_act3').ocultar();
				this.ef('desempenio_tit').ocultar();
				this.ef('eval_desempenio1').set_obligatorio(false); 
				this.ef('eval_desempenio1').ocultar();
				this.ef('eval_desempenio2').ocultar();
				this.ef('eval_desempenio3').ocultar();
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