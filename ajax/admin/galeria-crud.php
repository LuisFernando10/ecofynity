
    <?php

    //Importamos los archivos necesarios para el tratamiento de los datos
    require_once(dirname(__FILE__).'/../../security/secure-session.php');
    require_once(dirname(__FILE__).'/../../config-import.php');

    //Obtenemos los datos relevantes para los procesos
    $id_user = Security::GetSessionUserId();

    //Obtenemos los datos enviados por el Ajax
    $galeria_id = filter_input(INPUT_POST, 'galeria_id', FILTER_SANITIZE_NUMBER_INT);
    $galeria_name = filter_input(INPUT_POST, 'galeria_name', FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);


    //Validamos el tipo de 'acción'
    if ($action == 'INSERT') {
        //Nós obtemos as propiedades do file
        $producto_file = $_FILES['galeria_file'];

        //Validamos si existen archivos para insertar
        if (!empty($producto_file)) {

            //Creamos variable para guardar el array con información del archivo ó la vaciamos
            $file_document = empty($producto_file) ? '' : $producto_file;

            //Porcessamos o arquivo para mover ele ao caminho estebelecida
            $file_proccess = Files::uploadFile($file_document);

            //Validamos successo ou error no proceso do 'upload'
            if ($file_proccess != false) {

                //Realizamos la inserción de los campos de la plantilla en la BD
                $id_galeria = Galeria::insertGaleria($galeria_name, $file_proccess);

                //Validamos si la inserción tuvo éxito o no
                if ($id_galeria != null && is_numeric($id_galeria))
                    $response = array(
                        'status' => '200',
                        'message' => 'Galería Creada',
                        'id_galeria' => $id_galeria
                    );
                elseif ($id_galeria == 'existing_galeria')
                    $response = array(
                        'status' => '500',
                        'message' => 'Nombre Galería Existe',
                        'id_galeria' => $id_galeria
                    );
                else
                    $response = array(
                        'status' => '500',
                        'message' => 'Error al registrar',
                        'id_galeria' => null
                    );
            }
        }else{
            $response = array(
                'status' => '500',
                'message' => 'Error al registrar',
                'id_galeria' => null
            );
        }

    }
    elseif ($action == 'DELETE'){


        //Nos executamos o método que deleta o galeria e o arquivo do servidor
        $delete_galeria = Galeria::deleteGaleria($galeria_id);

        //Nón validamos o sucesso da operacao
        if ($delete_galeria){

            $response = array(
                'status' => '200',
                'message' => 'Galería eliminada'
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