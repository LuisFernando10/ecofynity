
    <?php

    //Importamos los archivos necesarios para el tratamiento de los datos
    require_once(dirname(__FILE__).'/../../security/secure-session.php');
    require_once(dirname(__FILE__).'/../../config-import.php');

    //Obtenemos los datos relevantes para los procesos
    $id_user = Security::GetSessionUserId();

    //Obtenemos los datos enviados por el Ajax
    $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_SANITIZE_NUMBER_INT);
    $producto_name = filter_input(INPUT_POST, 'producto_name', FILTER_SANITIZE_STRING);
    $producto_description = filter_input(INPUT_POST, 'producto_description', FILTER_SANITIZE_STRING);
    $producto_categoria = filter_input(INPUT_POST, "producto_categoria", FILTER_SANITIZE_STRING);
    $producto_price = filter_input(INPUT_POST, "producto_price", FILTER_SANITIZE_STRING);
    $producto_descuento = filter_input(INPUT_POST, "producto_descuento", FILTER_SANITIZE_STRING);
    $producto_file_current_name = filter_input(INPUT_POST, "producto_file_current_name", FILTER_SANITIZE_STRING);
    $producto_estado = filter_input(INPUT_POST, "producto_estado", FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);



    //Validamos el tipo de 'acción'
    if ($action == 'INSERT'){

        //Nós obtemos as propiedades do file
        $producto_file = $_FILES['producto_file'];

        //Validamos si existen archivos para insertar
        if (!empty($producto_file)){

            //Creamos variable para guardar el array con información del archivo ó la vaciamos
            $file_document = empty($producto_file) ? '' : $producto_file;

            //Porcessamos o arquivo para mover ele ao caminho estebelecida
            $file_proccess = Files::uploadFile($file_document);

            //Validamos successo ou error no proceso do 'upload'
            if ($file_proccess != false){

                //Realizamos la inserción de los campos de la plantilla en la BD
                $id_room = Producto::insertProducto($producto_categoria,$producto_name, $producto_description, $file_proccess,$producto_price,$producto_descuento);

                //Validamos si la inserción tuvo éxito o no
                if ($id_room != null && is_numeric($id_room))
                    $response = array(
                        'status' => '200',
                        'message' => 'Quarto Registrado',
                        'id_room' => $id_room
                    );
                elseif ($id_room == 'existing_producto')
                    $response = array(
                        'status' => '500',
                        'message' => 'O nome do producto já existe',
                        'id_room' => $id_room
                    );
                else
                    $response = array(
                        'status' => '500',
                        'message' => 'Error ao registrar',
                        'id_room' => null
                    );
            }
            else
                $response = array(
                    'status' => '500',
                    'message' => 'Error al registrar',
                    'id_room' => null
                );
        }
    }
    elseif ($action == 'UPDATE'){




        //Nós validamos se o array ($_FILES) está vacío para nao ter problemas con o (Notice do PHP)
        if (empty($_FILES))
            $producto_file = NULL;
        else
            //Nós obtemos as propiedades do file
            $producto_file = $_FILES['producto_file'];

        //Validamos si existen archivos para insertar
        if (isset($producto_file) && (!empty($producto_file) || $producto_file != NULL)){

            //Creamos variable para guardar el array con información del archivo ó la vaciamos
            $file_document = empty($producto_file) ? '' : $producto_file;
            $data_producto = Producto::getAll(NULL,NULL,NULL,$producto_id);
            $imagenn_anterior = $data_producto[0]['imagen'];
            //Deletamos o arquivo no servidor antes de atualizar pelo novo arquivo

            $delete_file = true;
            if ($imagenn_anterior != '')
                $delete_file = Files::deleteFileServer($imagenn_anterior);

            //Nos validamos se realmente se deleteu o arquivo
            if ($delete_file == true){

                //Porcessamos o arquivo para mover ele ao caminho estebelecida
                $file_proccess = Files::uploadFile($file_document);

                //Validamos successo ou error no proceso do 'upload'
                if ($file_proccess != false){

                    //Realizamos la inserción de los campos de la plantilla en la BD
                    $update_room = Producto::updateProducto($producto_id,$producto_categoria,$producto_name, $producto_description, $file_proccess,$producto_price,$producto_descuento,$producto_estado);

                    //Validamos si la inserción tuvo éxito o no
                    if ($update_room != false)
                        $response = array(
                            'status' => '200',
                            'message' => 'Categoría Actualizada',
                            'id_room' => $update_room
                        );
                    elseif ($update_room == 'existing_producto')
                        $response = array(
                            'status' => '500',
                            'message' => 'Nombre Categría existe',
                            'id_room' => $update_room
                        );
                    else
                        $response = array(
                            'status' => '500',
                            'message' => 'Error al Actualizar',
                            'id_room' => null
                        );
                }
                else
                    $response = array(
                        'status' => '500',
                        'message' => 'Error ao Atualizary',
                        'id_room' => null
                    );
            }
            else
                $response = array(
                    'status' => '500',
                    'message' => 'Error ao Atualizar',
                    'id_room' => null
                );
        }
        else{

            //Realizamos la inserción de los campos de la plantilla en la BD
            $update_room = Producto::updateProducto($producto_id,$producto_categoria,$producto_name, $producto_description, NULL,$producto_price,$producto_descuento,$producto_estado);

            //Validamos si la inserción tuvo éxito o no
            if ($update_room != false)
                $response = array(
                    'status' => '200',
                    'message' => 'Quarto Atualizado',
                    'id_room' => $update_room
                );
            elseif ($update_room == 'existing_producto')
                $response = array(
                    'status' => '500',
                    'message' => 'O nome do producto já existe',
                    'id_room' => $update_room
                );
            else
                $response = array(
                    'status' => '500',
                    'message' => 'Error ao Atualizar',
                    'id_room' => null
                );
        }
    }
    elseif ($action == 'DELETE'){

        //Obtemos os dados do producto
        $data_room = Producto::getAll(NULL,NULL,NULL,$producto_id,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

        //Obtemos o nome do arquivo
        $imagen_name = $data_room[0]['imagen'];

        //Nos executamos o método que deleta o producto e o arquivo do servidor
        $delete_producto = Producto::deleteProducto($producto_id);

        //Nón validamos o sucesso da operacao
        if ($delete_producto){

            //Deletamos o arquivo no servidor antes de atualizar pelo novo arquivo
            $delete_file = Files::deleteFileServer($imagen_name);

            $response = array(
                'status' => '200',
                'message' => 'Producto Eliminado'
            );
        }
        else
            $response = array(
                'status' => '500',
                'message' => 'Error'
            );
    }

    //Retornamos la respuesta en formato Json
    echo json_encode($response);