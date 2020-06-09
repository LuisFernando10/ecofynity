
    <?php

        /**
         * @Description: Documento que gestiona los CRUD de la información para 'Opiniones'
         * @User: luis.chamorro
         * @Date: 03-mar-2020
         */

        class Galeria
        {
            /**
             * @Description: Metodo que obtem os dados correspondentes aos 'Quartos'
             */
            static function getAll($page = NULL, $pagination = NULL, $type = NULL, $id_galeria = NULL, $nombre = NULL) {

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

                //Filtro por '$id_galeria'
                if ($id_galeria !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND galeria.id_galeria = "%s"';
                    $this_condition = sprintf($this_condition, $id_galeria);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$nombre'
                if ($nombre !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND galeria.nombre = "%s"';
                    $this_condition = sprintf($this_condition, $nombre);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Evaluamos el tipo de consulta
                if ($type == 'count') {
                    $sql_select = "count(*) as count";
                    $sql_limit = '';
                }
                else {
                    $sql_select = "
                            galeria.id_galeria,
                            galeria.nombre,
                            galeria.imagen
                        ";

                    $sql_limit = "LIMIT $limit_start, $pagination";
                }

                //Preparamos el query
                $sql = "
                    SELECT 
                        $sql_select
                    FROM 
                        galeria
                    WHERE
                        1+1=2
                        $conditions
                    ORDER BY 
                        galeria.nombre
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
             * @Description: Metodo que inserta um galeria no BD
             */
            static function insertGaleria($nombre = NULL, $file_proccess = NULL){

                // ** Proceso para validar si existe un cuarto con el mismo nombre **
                $existing_galeria = Galeria::getAll(NULL,NULL,NULL,NULL, $nombre);

                if ($existing_galeria != NULL)
                    return 'existing_galeria';
                else{
                    //Preparamos Query
                    $sql = "
                        INSERT INTO galeria (
                            nombre,
                            imagen
                        )
                        VALUES (
                            '%s',
                            '%s'
                        )
                    ";

                    //Nos substitui os valores
                    $sql = sprintf($sql,
                        $nombre,
                        $file_proccess
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

            /**
             * @Description: Metodo que atualiza um galeria no BD
             */
            static function updateGaleria($id_galeria = NULL, $nombre = NULL,$file_proccess = NULL){

                // ** Proceso para validar si existe un cuarto con el mismo nombre **
                $existing_galeria = Galeria::getAll(NULL,NULL,NULL,NULL, $nombre);

                //Variavel de control pra controlar a existencia do mesmo nombre em outra tabela
                $existing_control = true;

                //Validamos se a variável é uma matriz (array) pra não possuir erros com o PHP ao momento de percorrer o 'foreach'
                if (is_array($existing_galeria) || is_object($existing_galeria))
                    //Percorremos os dados pra validar se o nombre que vai-se salvar já existe com o meu Id
                    foreach ($existing_galeria as $value){

                        //Validamos se o nombre existe no mesmo Id que o atual da edição
                        if ($value['id_galeria'] !== $id_galeria)
                            $existing_control = false;
                    }



                //Validamos se o nombre existe em outra tabela ou não
                if ($existing_control == false)
                    return 'existing_galeria';
                else{

                    //Validamos se existe ou nao uma imagenm pra atualizar
                    if ($file_proccess != NULL)
                        $update_imagen = ", imagen = '$file_proccess'";
                    else
                        $update_imagen = "";

                    //Preparamos el Query
                    $sql = "
                        UPDATE galeria
                        SET
                            nombre = '%s'
                            $update_imagen
                        WHERE
                            id_galeria = '%s'
                    ";

                    //Reemplazamos valores
                    $sql = sprintf($sql,
                        $nombre,
                        $id_galeria
                    );

                    //Ejecutamos el Query
                    $result = DataBase::query($sql);
                    //Retornamos el resultado de la consulta (true-false)
                    return $result;
                }
            }

            /**
             * @Description: Metodo que deletea um galeria desde o BD
             */
            static function deleteGaleria($id_galeria = NULL){

                //A gente prepara o query
                $sql = "
                    DELETE FROM
                        galeria 
                    WHERE 
                        id_galeria = '%s'
                ";

                //A gente substituimos os valores
                $sql = sprintf($sql,
                    $id_galeria
                );

                //A gente executa o query
                $result = DataBase::query($sql);

                //A gente retorna o resultado da consulta (true-false)
                return $result;
            }
        }