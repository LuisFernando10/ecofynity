
    <?php

    //Importamos los archivos necesarios para el tratamiento de los datos
    require_once(dirname(__FILE__).'/../../security/secure-session.php');
    require_once(dirname(__FILE__).'/../../config-import.php');

    //Obtenemos los datos relevantes para los procesos
    $id_user = Security::GetSessionUserId();

    //Obtenemos los datos enviados por el Ajax
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_SANITIZE_NUMBER_INT);
    $categoria_name = filter_input(INPUT_POST, 'categoria_name', FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);


    //Validamos el tipo de 'acción'
    if ($action == 'INSERT') {
        //Nós obtemos as propiedades do file
        $producto_file = $_FILES['categoria_file'];

        //Validamos si existen archivos para insertar
        if (!empty($producto_file)) {

            //Creamos variable para guardar el array con información del archivo ó la vaciamos
            $file_document = empty($producto_file) ? '' : $producto_file;

            //Porcessamos o arquivo para mover ele ao caminho estebelecida
            $file_proccess = Files::uploadFile($file_document);

            //Validamos successo ou error no proceso do 'upload'
            if ($file_proccess != false) {

                //Realizamos la inserción de los campos de la plantilla en la BD
                $id_categoria = Category::insertCategoria($categoria_name, $file_proccess);

                //Validamos si la inserción tuvo éxito o no
                if ($id_categoria != null && is_numeric($id_categoria))
                    $response = array(
                        'status' => '200',
                        'message' => 'Categoría Creada',
                        'id_categoria' => $id_categoria
                    );
                elseif ($id_categoria == 'existing_categoria')
                    $response = array(
                        'status' => '500',
                        'message' => 'Nombre Categoría Existe',
                        'id_categoria' => $id_categoria
                    );
                else
                    $response = array(
                        'status' => '500',
                        'message' => 'Error al registrar',
                        'id_categoria' => null
                    );
            }
        }else{
            $response = array(
                'status' => '500',
                'message' => 'Error al registrar',
                'id_categoria' => null
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
        if (isset($producto_file) && (!empty($producto_file) || $producto_file != NULL)) {

            //Creamos variable para guardar el array con información del archivo ó la vaciamos
            $file_document = empty($producto_file) ? '' : $producto_file;

            $data_producto = Category::getAll(NULL, NULL, NULL, $categoria_id);
            $previous_image = $data_producto[0]['imagen'];

            //Deletamos o arquivo no servidor antes de atualizar pelo novo arquivo
            $delete_file = true;

            if ($previous_image!='')
                $delete_file = Files::deleteFileServer($previous_image);

            //Nos validamos se realmente se deleteu o arquivo
            if ($delete_file == true) {

                //Porcessamos o arquivo para mover ele ao caminho estebelecida
                $file_proccess = Files::uploadFile($file_document);

                //Validamos successo ou error no proceso do 'upload'
                if ($file_proccess != false) {

                    //Realizamos la inserción de los campos de la plantilla en la BD
                    $update_categoria = Category::updateCategoria($categoria_id, $categoria_name,$file_proccess);

                    //Validamos si la inserción tuvo éxito o no
                    if ($update_categoria != false)
                        $response = array(
                            'status' => '200',
                            'message' => 'Categoría Actualizada',
                            'id_categoria' => $update_categoria
                        );
                    elseif ($update_categoria == 'existing_categoria')
                        $response = array(
                            'status' => '500',
                            'message' => 'El nombre de esta Categoría no está disponible.',
                            'id_categoria' => $update_categoria
                        );
                    else
                        $response = array(
                            'status' => '500',
                            'message' => 'Error al Actualizar',
                            'id_categoria' => null
                        );
                }
            }
        }
        else{
            //Realizamos la inserción de los campos de la plantilla en la BD
            $update_categoria = Category::updateCategoria($categoria_id, $categoria_name,NULL);

            //Validamos si la inserción tuvo éxito o no
            if ($update_categoria != false)
                $response = array(
                    'status' => '200',
                    'message' => 'Categoría Actualizada',
                    'id_categoria' => $update_categoria
                );
            elseif ($update_categoria == 'existing_categoria')
                $response = array(
                    'status' => '500',
                    'message' => 'Nombre Categría existe',
                    'id_categoria' => $update_categoria
                );
        }

    }
    elseif ($action == 'DELETE'){

        //Nos executamos o método que deleta o categoria e o arquivo do servidor
        $delete_categoria = Category::deleteCategoria($categoria_id);

        //Nón validamos o sucesso da operacao
        if ($delete_categoria){

            $response = array(
                'status' => '200',
                'message' => 'Categoría eliminada'
            );
        }
        else
            $response = array(
                'status' => '500',
                'message' => 'Error al Eliminar'
            );
    }

    //Retornamos la respuesta en formato Json
    echo json_encode($response);