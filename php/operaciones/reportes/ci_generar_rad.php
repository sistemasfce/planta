<?php
class ci_generar_rad extends planta_ci
{
	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(planta_ei_formulario $form)
	{
		$quote = '"';
		$path_toba = toba::proyecto()->get_path(). '/www/temp/';
		
		$filename = $path_toba.'rad.csv';
		$ubicacion = 3;
		$data = toba::consulta_php('co_asignaciones')->get_rad($ubicacion);
		$handle = fopen($filename, 'w');
			// Cargo al archivo la cabecera
			//fputcsv($handle, $lista, $delimiter = '|');
			// Armo las filas con los datos de la consulta y lo cargo en el archivo csv
		foreach ($data as $rows) {
			fputcsv($handle, array($rows['ubicacion_desc'], $rows['tipo_doc'], $rows['documento'], $rows['nombre_completo'], $rows['codigo'], $rows['descripcion'], $rows['fecha'], $rows['fecha']), $delimiter = ';');
		}        
			fclose($handle); 

		$filename = $path_toba.'rad_es.csv';
		$ubicacion = 2;
		$data = toba::consulta_php('co_asignaciones')->get_rad($ubicacion);
		$handle = fopen($filename, 'w');
		foreach ($data as $rows) {
			fputcsv($handle, array($rows['ubicacion_desc'], $rows['tipo_doc'], $rows['documento'], $rows['nombre_completo'], $rows['codigo'], $rows['descripcion'], $rows['fecha'], $rows['fecha']), $delimiter = ';');
		}        
		fclose($handle);   

		$filename = $path_toba.'rad_cr.csv';
		$ubicacion = 1;
		$data = toba::consulta_php('co_asignaciones')->get_rad($ubicacion);
		$handle = fopen($filename, 'w');
		foreach ($data as $rows) {
			fputcsv($handle, array($rows['ubicacion_desc'], $rows['tipo_doc'], $rows['documento'], $rows['nombre_completo'], $rows['codigo'], $rows['descripcion'], $rows['fecha'], $rows['fecha']), $delimiter = ';');
		}        
		fclose($handle);           
	
		$temp_archivo = toba::proyecto()->get_www_temp('rad.csv');
		//todos es un comando para convertir a formato windows la codificacion
		exec('todos '. $temp_archivo['path']);
		$tamanio = round(filesize($temp_archivo['path']) / 1024);
		$datos['vinculo_archivo'] = "<a href='{$temp_archivo['url']}'target='_blank'>Click derecho, guardar archivo como...</a>";
		$datos['archivo'] = 'rad.csv'. ' - Tam.: '.$tamanio. ' KB';                                   

		$temp_archivo = toba::proyecto()->get_www_temp('rad_es.csv');
		exec('todos '. $temp_archivo['path']);
		$tamanio = round(filesize($temp_archivo['path']) / 1024);
		$datos['vinculo_archivo_es'] = "<a href='{$temp_archivo['url']}'target='_blank'>Click derecho, guardar archivo como...</a>";
		$datos['archivo_es'] = 'rad_es.csv'. ' - Tam.: '.$tamanio. ' KB';                               

		$temp_archivo = toba::proyecto()->get_www_temp('rad_cr.csv');
		exec('todos '. $temp_archivo['path']);
		$tamanio = round(filesize($temp_archivo['path']) / 1024);
		$datos['vinculo_archivo_cr'] = "<a href='{$temp_archivo['url']}'target='_blank'>Click derecho, guardar archivo como...</a>";
		$datos['archivo_cr'] = 'rad_cr.csv'. ' - Tam.: '.$tamanio. ' KB';                
				
		$form->set_datos($datos);
	}    
}
?>