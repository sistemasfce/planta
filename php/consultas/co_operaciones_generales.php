<?php
 
class co_operaciones_generales
{
    function get_paises($pais=null, $where='')
    {   
        $filtro = 'WHERE true';
        if (!is_null($pais)) {
        	$pais = toba::db()->quote($pais);
                $filtro .= " AND mug_paises.pais = $pais"; 
        }   
        if ($where) {
                $filtro .= " AND $where";
        }   
                $sql = "SELECT pais as valor,
                               nombre as descr,
                               nombre || ' - (' || pais || ')'  as descr2
                        FROM   mug_paises
                        $filtro
                        ORDER BY nombre
                ";
       return toba::db()->consultar($sql);
   }	

   /** 
     Retorna las provincias para un pais dado.
   */
   function get_provincias_pais($pais=null)
   {   
                $where = '';
                if (!is_null($pais)) {
                        $pais = toba::db()->quote($pais);
                        $where .= " AND mug_paises.pais = $pais";
                }
                $sql = "SELECT          mug_provincias.provincia as valor,
                                                        mug_provincias.nombre as descr
                                FROM            mug_provincias,
                                                        mug_paises
                                WHERE           mug_provincias.pais = mug_paises.pais
                                                        $where
                                ORDER BY        mug_provincias.nombre
                ";
                return toba::db()->consultar($sql);
   }

   /**
     Retorna un listado de localidades con sus departamentos, provincias y países asociados.
   */
   function get_listado_localidades($con_CP, $filtro='')
   {
                $where = '';
                $select = '';
                $valor_cp = '';
                $from = '';

                if ($filtro != '') {
                        $where .= " AND $filtro";
                }

                if ($con_CP) {
                        $select = ' DISTINCT ON (mug_localidades.nombre,mug_localidades.localidad) ';
                        $valor_cp = " || ':' || COALESCE(mug_cod_postales.codigo_postal, '') ";
                        $from = ' LEFT OUTER JOIN mug_cod_postales ON mug_localidades.localidad = mug_cod_postales.localidad ';
                }

                $sql = "SELECT  $select
                                mug_localidades.localidad $valor_cp as localidad_valor,
                                mug_localidades.localidad,
                                mug_localidades.nombre as localidad_nombre,
                                mug_paises.pais as pais_valor,
                                mug_paises.nombre as pais_nombre,
                                mug_provincias.provincia as provincia_valor,
                                mug_provincias.nombre as provincia_nombre,
                                mug_dptos_partidos.dpto_partido as departamento_valor,
                         mug_dptos_partidos.nombre as departamento_nombre,
                         mug_localidades.nombre || ' - ' || mug_dptos_partidos.nombre || ' - ' || mug_provincias.nombre || ' - ' || mug_paises.nombre as identificador_localidad
                        FROM    mug_localidades
                                $from,
                                mug_dptos_partidos,
                                mug_provincias,
                                mug_paises
                        WHERE   mug_localidades.dpto_partido = mug_dptos_partidos.dpto_partido
                                AND     mug_dptos_partidos.provincia = mug_provincias.provincia
                                AND     mug_provincias.pais = mug_paises.pais
                                $where
                        ORDER BY localidad_nombre
                ";
                return toba::db()->consultar($sql);
  }

  /**
    Obtiene la clave y descripción de una localidad a partir del id dado para el ef_popup de Localidad.
  */
  static function get_localidad($id=null)
  {
                if (! isset($id) || trim($id) == '') {
                        return array();
                }
                $id = toba::db()->quote($id);
                $sql = "SELECT  mug_localidades.localidad as valor,
                                mug_localidades.nombre || ' - ' || mug_dptos_partidos.nombre || ' - ' || mug_provincias.nombre || ' - ' || mug_paises.nombre as descr
                        FROM    mug_localidades,
                                                mug_dptos_partidos,
                                                mug_provincias,
                                                mug_paises
                        WHERE   mug_localidades.dpto_partido = mug_dptos_partidos.dpto_partido AND
                                                mug_dptos_partidos.provincia = mug_provincias.provincia AND
                                                mug_provincias.pais = mug_paises.pais AND
                                                mug_localidades.localidad = $id
                ";
                $result = toba::db()->consultar($sql);
                if (!empty($result)) {
                        return $result[0]['descr'];
               }
   }


}
?>
