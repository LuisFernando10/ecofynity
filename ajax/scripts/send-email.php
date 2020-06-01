
    <?php

        /**
         * @Description: Documento que procesa y gestiona la informacion para enviar correos electrónicos
         * @User: luis.chamorro
         * @Date: 31-may-2020
         */

        //Cargamos la configuracion global
        require_once dirname(__FILE__).'/../../config-import.php';

        //Obtenemos los datos del Ajax
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, array('options'=>array('default'=>NULL)));
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL, array('options'=>array('default'=>NULL)));
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT, array('options'=>array('default'=>NULL)));
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING, array('options'=>array('default'=>NULL)));
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING, array('options'=>array('default'=>'CONTACT')));

        //Evaluamos el tipo de acción
        if ($action === 'CONTACT'){

            //Enviamos el 'email'
            $send_email = GeneralMethods::sendEmail(
                'ventas@ecofynity.com.co',
                'Ventas Ecofynity',
                $text);

            //Validamos si se envió el correo
            if ($send_email === '200'){
                $response = [
                    'status' => '200',
                    'message' => 'Correo Electrónico enviado!!'
                ];
            }
            else
                $response = [
                    'status' => '500',
                    'message' => 'Ocurrió un error, intenta nuevamente!!'
                ];
        }

        //Retornamos la respuesta en formato JSON
        echo json_encode($response);
