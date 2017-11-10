<?php
	echo '<div class="logo">';
	echo toba_recurso::imagen_proyecto('logo_grande.gif', true);
	echo '</div>';
      $perfil = toba::usuario()->get_perfiles_funcionales();
      if ($perfil[0] == "docente") {
          toba::vinculador()->navegar_a("planta","280000078");
          return;
      }

?>
