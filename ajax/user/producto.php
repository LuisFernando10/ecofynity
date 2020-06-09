
    <?php

    //Importamos los archivos necesarios para el tratamiento de los datos

    require_once(dirname(__FILE__).'/../../config-import.php');



    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);


    //Validamos el tipo de 'acción'
    if ($action == 'SEARCH_PRODUCTO') {
        $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_SANITIZE_STRING);

        $data_producto = Producto::getAll(NULL,NULL,NULL,$id_producto);
        //Validamos si la inserción tuvo éxito o no
        if ($data_producto != null )
            $response = array(
                'status' => '200',
                'message' => '',
                'data' => $data_producto[0]
            );
        else
            $response = array(
                'status' => '500',
                'message' => 'Error al consultar'
            );


    }else     if ($action == 'SEARCH_GALERIA') {
        $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_SANITIZE_STRING);

        $data_producto = Galeria::getAll(NULL,NULL,NULL,$id_producto);
        //Validamos si la inserción tuvo éxito o no
        if ($data_producto != null )
            $response = array(
                'status' => '200',
                'message' => '',
                'data' => $data_producto[0]
            );
        else
            $response = array(
                'status' => '500',
                'message' => 'Error al consultar'
            );


    }
    echo json_encode($response);