
    <?php

        /**
         * @Description: Documento que gestiona los CRUD de la información para 'Correos Electrónicos'
         * @User: luis.chamorro
         * @Date: 19-jun-2020
         */

        class Emails
        {
            static function getAll($page = NULL, $pagination = NULL, $type = NULL, $id_correo = NULL, $nombre = NULL, $correo = NULL, $telefono = NULL, $mensaje = NULL, $fecha = NULL, $tipo = NULL, $estado = NULL) {

                //Valor por defecto para 'page'
                if (isset($page) && $page != NULL && is_numeric($page)){
                    /* Se deja igual */
                }
                else
                    $page = 1;

                //Valor por defecto para 'pagination'
                if (isset($pagination) && $pagination != NULL && is_numeric($pagination)) {
                    /* Se deja igual */
                }
                else
                    $pagination = constant("PAGINATION");

                //Valor por defecto para tipo (normal - count)
                if (isset($type) && $type != NULL) {
                    /* Se deja igual */
                }
                else
                    $type = "normal";

                //Se calcula desde que registro se va a listar segun la paginacion
                $limit_start = ($page * $pagination) - $pagination;

                //Construimos las condiciones de la consulta
                $conditions = "";

                //Filtro por '$id_correo'
                if ($id_correo !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.id_correo = "%s"';
                    $this_condition = sprintf($this_condition, $id_correo);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$nombre'
                if ($nombre !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.nombre = "%s"';
                    $this_condition = sprintf($this_condition, $nombre);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$correo'
                if ($correo !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.correo = "%s"';
                    $this_condition = sprintf($this_condition, $correo);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$telefono'
                if ($telefono !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.telefono = "%s"';
                    $this_condition = sprintf($this_condition, $telefono);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$fecha'
                if ($fecha !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.fecha = "%s"';
                    $this_condition = sprintf($this_condition, $fecha);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$tipo'
                if ($tipo !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.tipo = "%s"';
                    $this_condition = sprintf($this_condition, $tipo);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$estado'
                if ($estado !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND correo.estado = "%s"';
                    $this_condition = sprintf($this_condition, $estado);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Evaluamos si es una consulta 'normal' o de 'count'
                if ($type == 'count') {
                    $sql_select = "count(*) as count";
                    $sql_limit = '';
                }
                else {
                    $sql_select = "
                            correo.id_correo,
                            correo.nombre,
                            correo.correo,
                            correo.telefono,
                            correo.mensaje,
                            correo.fecha,
                            correo.tipo,
                            correo.estado
                        ";

                    $sql_limit = "LIMIT $limit_start, $pagination";
                }

                $sql = "
                    SELECT 
                        $sql_select
                    FROM 
                        correo
                    WHERE
                        1+1=2
                        $conditions
                    ORDER BY 
                        correo.fecha DESC
                    $sql_limit
                ";

                //Ejecutamos la consulta
                $result = DataBase::query($sql);

                if (isset($result[0]) && $result != NULL) {

                    if ($type == 'count') {

                        //Se calcula el total de paginas con esta configuracion
                        $total_pages = ceil(($result[0]['count']) / $pagination);

                        return array(
                            "count" => $result[0]['count'],
                            "pagination" => "" . $pagination,
                            "page" => "" . $page,
                            "total_pages" => "" . $total_pages,
                        );
                    }
                    else
                        return $result;
                }
                else
                    return NULL;
            }

            /**
             * @Description: Metodo que inserta un correo en la BD
             */
            static function insertEmail($nombre = NULL, $correo = NULL, $telefono = NULL, $mensaje = NULL, $tipo = NULL){

                //Preparamos Query
                $sql = "
                    INSERT INTO correo (
                        nombre,
                        correo,
                        telefono,
                        mensaje,
                        fecha,
                        tipo,
                        estado
                    )
                    VALUES (
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        NOW(),
                        '%s',
                        'sin_leer'
                    )
                ";

                //Reemplazamos los valores
                $sql = sprintf($sql,
                    $nombre,
                    $correo,
                    $telefono,
                    $mensaje,
                    $tipo
                );

                //Ejecutamos el query
                $result = DataBase::query($sql);

                //Validamos si la consulta se ejecuto correctamente
                if ($result != NULL)
                    return $result;
                else
                    return false;
            }
        }