<?php

class comunes {
    //tabla estados
    const estado_activo = 1;
    const estado_pendiente = 2;
    const estado_historico = 3;
    const estado_con_licencia_t = 4;
    const estado_con_licencia_p = 5;
    const estado_vigente = 6;
    const estado_con_licencia = 7;
    const estado_inactivo = 8;
    const estado_sin_efecto = 10;
    const estado_condicionada = 11;
    const estado_efectiva = 12;
    const estado_no_aplica = 13;
    const estado_no_coresponde = 14;
    const estado_finalizada = 15;
    
    //tabla designaciones_tipos
    const desig_alta = 1;
    const desig_licencia = 2;
    const desig_renuncia = 3;
    const desig_renuncia_jub = 4;
    const desig_extincion = 5;
    const desig_no_requisitos = 6;
    const desig_remocion = 7;
    const desig_baja = 8;
    const desig_licencia_mji = 9;
    const desig_sin_efecto = 10;       
   
    //tabla categorias
    const cat_titular = 1; 
    const cat_asociado = 2; 
    const cat_adjunto = 3; 
    const cat_tjp = 4; 
    const cat_aux1 = 5; 
    const cat_aux2 = 6;     

    //tabla dedicaciones
    const ded_simple = 1;  
    const ded_semi = 2; 
    const ded_exc = 3; 
    const ded_exc25 = 4; 
    const ded_exc50 = 5; 
    const ded_exc75 = 6; 

    //tabla caracteres
    const car_interino = 1;
    const car_regular = 2;
    const car_invitado = 3;
    const car_honorario = 4;
    const car_contratado = 5;
    const car_visitante = 6;  
    const car_libre = 7;  
    const car_noc = 8;  
    const car_suplente = 9;       

}