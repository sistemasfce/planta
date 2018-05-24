<?php

class co_acreeditacion
{
    function get_cantidad_docentes_designaciones()
    {
        $sql = "  
            -- docentes activos
            SELECT nombre_completo,
                    COUNT (designacion) as cantidad_designaciones,
                    SUM (carga_horaria::Int) as suma_horas

            FROM (

                SELECT 	personas.apellido || ', ' || personas.nombres as nombre_completo,
                        designaciones.designacion,
                        designaciones.carga_horaria
                FROM 	designaciones LEFT OUTER JOIN personas ON (designaciones.persona = personas.persona)
                WHERE 	designaciones.estado = 1
                        AND designaciones.dimension = 1
                        AND designaciones.categoria not in (14,15,44,10) --decano,vice,delegado,secretario de facultad

                UNION ALL

                -- docentes con licencias parciales
                SELECT 	personas.apellido || ', ' || personas.nombres as nombre_completo,
                        designaciones.designacion,
                        designaciones.carga_horaria
                FROM 	designaciones LEFT OUTER JOIN personas ON (designaciones.persona = personas.persona)
                        LEFT OUTER JOIN designaciones as des2 ON designaciones.designacion_padre = des2.designacion
                WHERE 	designaciones.estado = 6 -- licencia vigente
                        AND designaciones.designacion_tipo = 2 -- licencia
                        AND des2.estado = 5 -- licencia parcial
                        AND designaciones.dimension = 1
                        AND designaciones.categoria not in (14,15,44,10) --decano,vice,delegado,secretario de facultad

                UNION ALL

                -- docentes funcionarios
                SELECT 	personas.apellido || ', ' || personas.nombres as nombre_completo,
                        designaciones.designacion,
                        designaciones.carga_horaria
                FROM 	designaciones LEFT OUTER JOIN personas ON (designaciones.persona = personas.persona)
                        LEFT OUTER JOIN designaciones as des2 ON designaciones.designacion_padre = des2.designacion
                WHERE 	designaciones.estado = 6 -- licencia vigente
                        AND designaciones.designacion_tipo = 2 -- licencia
                        AND des2.estado = 4 -- licencia total
                        AND designaciones.dimension = 1
                        AND designaciones.persona in (841,1074,1147,863,1039,1384) --daniel, yanina, julio ib, marcela, cristina, celeste

            ) as c1
            GROUP BY nombre_completo
            ORDER BY nombre_completo
        ";
        return toba::db()->consultar($sql);
    }
    
    function get_categorias_dedicaciones()
    {
        $sql = "
        SELECT 	categoria,
                dedicacion,
                CASE WHEN categoria like 'Ayudante de 2%' THEN 6
                        WHEN categoria like 'Ayudante de 1%' THEN 5
                        WHEN categoria like 'Jefe de Trabajos%' THEN 4
                        WHEN categoria = 'Profesor Adjunto' THEN 3
                        WHEN categoria = 'Profesor Asociado' THEN 2
                        WHEN categoria = 'Profesor Titular' THEN 1
                ELSE 7
                END as categoria_orden,	
                CASE WHEN dedicacion = 'Simple' THEN 5
                        WHEN dedicacion = 'Semiexclusiva' THEN 4
                        WHEN dedicacion = 'Semiexclusiva+Simple' THEN 3
                        WHEN dedicacion = 'Exclusiva' THEN 2
                        WHEN dedicacion = 'Exclusiva+Simple' THEN 1
                ELSE 6
                END as dedicacion_orden,
                cantidad
        FROM (

        SELECT 	categoria, 
                CASE 	WHEN sum = 10 THEN 'Simple'
                        WHEN sum = 20 THEN 'Semiexclusiva'
                        WHEN sum = 30 THEN 'Semiexclusiva+Simple'
                        WHEN sum = 40 THEN 'Exclusiva'
                        WHEN sum = 50 THEN 'Exclusiva+Simple'
                ELSE '--'
                END as dedicacion,
                COUNT (categoria) as cantidad
        FROM 	(
                -- consulta docentes activos
                SELECT categorias.descripcion as categoria, 
                        --designaciones.carga_horaria 
                        SUM(rtrim(designaciones.carga_horaria)::Int)
                FROM 	designaciones 
                        LEFT OUTER JOIN categorias ON designaciones.categoria = categorias.categoria
                        LEFT OUTER JOIN dedicaciones ON designaciones.dedicacion = dedicaciones.dedicacion
                WHERE 	designaciones.estado = 1 
                        AND designaciones.dimension = 1
                        AND designaciones.categoria not in (14,15,44,10) --decano,vice,delegado,secretario de facultad
                GROUP BY designaciones.persona, categorias.descripcion

                UNION ALL

                -- consulta docentes con licencias parciales
                SELECT 	categorias.descripcion as categoria, 
                        SUM(designaciones.carga_horaria::Int)
                FROM 	designaciones 
                        LEFT OUTER JOIN categorias ON designaciones.categoria = categorias.categoria
                        LEFT OUTER JOIN dedicaciones ON designaciones.dedicacion = dedicaciones.dedicacion
                        LEFT OUTER JOIN designaciones as des2 ON designaciones.designacion_padre = des2.designacion
                WHERE 	designaciones.estado = 6 -- licencia vigente
                        AND designaciones.designacion_tipo = 2 -- licencia
                        AND des2.estado = 5 -- licencia parcial
                        AND designaciones.dimension = 1
                        AND designaciones.categoria not in (14,15,44,10) --decano,vice,delegado,secretario de facultad
                GROUP BY designaciones.persona, categorias.descripcion

                UNION ALL

                -- consulta docente funcionarios, recupero sus designaciones licenciadas
                SELECT 	categorias.descripcion as categoria, 
                        SUM(designaciones.carga_horaria::Int)
                FROM 	designaciones 
                        LEFT OUTER JOIN categorias ON designaciones.categoria = categorias.categoria
                        LEFT OUTER JOIN dedicaciones ON designaciones.dedicacion = dedicaciones.dedicacion
                        LEFT OUTER JOIN designaciones as des2 ON designaciones.designacion_padre = des2.designacion
                WHERE 	designaciones.estado = 6 -- licencia vigente
                        AND designaciones.designacion_tipo = 2 -- licencia
                        AND des2.estado = 4 -- licencia total
                        AND designaciones.dimension = 1
                        AND designaciones.persona in (841,1074,1147,863,1039,1384) --daniel, yanina, julio ib, marcela, cristina, celeste
                GROUP BY designaciones.persona, categorias.descripcion	
                ) as c3

        GROUP BY c3.categoria, 
                 dedicacion

        ) as c2	 
        ORDER BY categoria_orden, dedicacion_orden            
        ";
        return toba::db()->consultar($sql);
    }
    
}
?>
