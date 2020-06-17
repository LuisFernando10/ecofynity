
    <?php

    //Importamos los archivos necesarios para el tratamiento de los datos
    require_once(dirname(__FILE__).'/../../config-import.php');

    //Obtenemos los datos enviados por el Ajax
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $id_category = filter_input(INPUT_POST, 'id_category', FILTER_SANITIZE_NUMBER_INT, array("options" => array("default" => NULL)));

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


    }
    else if ($action == 'SEARCH_GALERIA') {

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
    else if ($action == 'SELECT-PRODUCTS') {

        //Obtenemos los productos que corresponden a la respectiva 'categoría'
        $data_products = Producto::getAll(NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Activo',NULL, $id_category);

        //Validamos si se retornaron datos o no
        if ($data_products != NULL )
            $response = array(
                'status' => '200',
                'message' => 'ok',
                'data_products' => $data_products
            );
        else
            $response = array(
                'status' => '500',
                'message' => 'No hay productos disponibles en ésta categoría.'
            );
    }

    //Retornamos la respuesta en formato JSON
    echo json_encode($response);