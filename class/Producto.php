
    <?php

        /**
         * @Description: Documento que gestiona los CRUD de la información para 'Opiniones'
         * @User: luis.chamorro
         * @Date: 03-mar-2020
         */

        class Producto
        {
            /**
             * @Description: Metodo que obtem os dados correspondentes aos 'Quartos'
             */
            static function getAll($page = NULL, $pagination = NULL, $type = NULL, $id_producto = NULL, $nombre = NULL, $descripcion = NULL, $imagen = NULL, $precio = NULL,  $estado = NULL, $descuento = NULL, $id_categoria = NULL) {

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

                //Filtro por '$id_producto'
                if ($id_producto !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.id_producto = "%s"';
                    $this_condition = sprintf($this_condition, $id_producto);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$nombre'
                if ($nombre !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.nombre = "%s"';
                    $this_condition = sprintf($this_condition, $nombre);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$descripcion'
                if ($descripcion !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.descripcion = "%s"';
                    $this_condition = sprintf($this_condition, $descripcion);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$imagen'
                if ($imagen !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.imagen = "%s"';
                    $this_condition = sprintf($this_condition, $imagen);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$precio'
                if ($precio !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.precio = "%s"';
                    $this_condition = sprintf($this_condition, $precio);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$estado'
                if ($estado !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.estado = "%s"';
                    $this_condition = sprintf($this_condition, $estado);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$descuento'
                if ($descuento !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.descuento = "%s"';
                    $this_condition = sprintf($this_condition, $descuento);

                    $conditions .= $this_condition;
                    unset($this_condition);
                }

                //Filtro por '$id_categoria'
                if ($id_categoria !== NULL) {
                    unset($this_condition);
                    $this_condition = 'AND productos.id_categoria = "%s"';
                    $this_condition = sprintf($this_condition, $id_categoria);

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
                            productos.id_producto,
                            productos.nombre,
                            productos.descripcion,
                            productos.id_categoria,
                            productos.imagen,
                            productos.precio,
                            productos.estado,
                            productos.descuento,
                            categoria.nombre as categoria
                        ";

                    $sql_limit = "LIMIT $limit_start, $pagination";
                }

                //Preparamos el query
                $sql = "
                    SELECT 
                        $sql_select
                    FROM 
                        productos
                    INNER JOIN
                    categoria on productos.id_categoria = categoria.id_categoria 
                    WHERE
                        1+1=2
                        $conditions
                    ORDER BY 
                        productos.nombre
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
             * @Description: Metodo que inserta um producto no BD
             */
            static function insertProducto($id_categoria = NULL,$nombre = NULL, $descripcion = NULL, $imagen = NULL, $precio = NULL, $descuento = NULL, $estado = NULL){
                // ** Proceso para validar si existe un cuarto con el mismo nombre **
                $existing_producto = Producto::getAll(NULL,NULL,NULL,NULL, $nombre);

                if ($existing_producto != NULL)
                    return 'existing_producto';
                else{
                    //Preparamos Query
                    $sql = "
                        INSERT INTO productos (
                            id_categoria,
                            nombre,
                            descripcion,
                            imagen,
                            precio,
                            descuento,
                            estado
                        )
                        VALUES (
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                        )
                    ";

                    //Nos substitui os valores
                    $sql = sprintf($sql,
                        $id_categoria,
                        $nombre,
                        $descripcion,
                        $imagen,
                        $precio,
                        $descuento,
                        $estado
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
             * @Description: Metodo que atualiza um producto no BD
             */
            static function updateProducto($id_producto = NULL, $id_categoria = NULL, $nombre = NULL, $descripcion = NULL, $imagen = NULL, $precio = NULL, $descuento = NULL, $estado = NULL){

                // ** Proceso para validar si existe un cuarto con el mismo nombre **
                $existing_producto = Producto::getAll(NULL,NULL,NULL,NULL, $nombre);

                //Variavel de control pra controlar a existencia do mesmo nombre em outra tabela
                $existing_control = true;

                //Validamos se a variável é uma matriz (array) pra não possuir erros com o PHP ao momento de percorrer o 'foreach'
                if (is_array($existing_producto) || is_object($existing_producto))
                    //Percorremos os dados pra validar se o nombre que vai-se salvar já existe com o meu Id
                    foreach ($existing_producto as $value){

                        //Validamos se o nombre existe no mesmo Id que o atual da edição
                        if ($value['id_producto'] !== $id_producto)
                            $existing_control = false;
                    }

                //Validamos se o nombre existe em outra tabela ou não
                if ($existing_control == false)
                    return 'existing_producto';
                else{
                    //Validamos se existe ou nao uma imagenm pra atualizar
                    if ($imagen != NULL)
                        $update_imagen = "imagen = '$imagen',";
                    else
                        $update_imagen = "";

                    //Preparamos el Query
                    $sql = "
                        UPDATE productos
                        SET
                            id_categoria = '%s',
                            nombre = '%s',
                            descripcion = '%s',
                            $update_imagen
                            precio = '%s',
                            descuento = '%s',
                            estado = '%s'
                        WHERE
                            id_producto = '%s'
                    ";

                    //Reemplazamos valores
                    $sql = sprintf($sql,
                        $id_categoria,
                        $nombre,
                        $descripcion,
                        $precio,
                        $descuento,
                        $estado,
                        $id_producto
                    );

                    //Ejecutamos el Query
                    $result = DataBase::query($sql);
                    //Retornamos el resultado de la consulta (true-false)
                    return $result;
                }
            }

            /**
             * @Description: Metodo que deletea um producto desde o BD
             */
            static function deleteProducto($id_producto = NULL){

                //A gente prepara o query
                $sql = "
                    DELETE FROM
                        productos 
                    WHERE 
                        id_producto = '%s'
                ";

                //A gente substituimos os valores
                $sql = sprintf($sql,
                    $id_producto
                );

                //A gente executa o query
                $result = DataBase::query($sql);

                //A gente retorna o resultado da consulta (true-false)
                return $result;
            }
        }