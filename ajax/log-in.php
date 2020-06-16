
    <?php
        /**
         * @Description: Documento que procesa el log-in para direccionar a las respectivas vistas
         * @User: luis.chamorro
         * @Date: 14-feb-2020
         */

        //Importamos los archivos necesarios para el tratamiento de los datos
        require_once(dirname(__FILE__).'/../config-import.php');

        //Obtenemos el usuario y la contraseña
        $username = filter_input(INPUT_POST, "user_name", FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, "user_password", FILTER_SANITIZE_STRING);

        //Se valida si el usuario y contrase es valido
        $result_validation_user_password = Users::validateUserAccessData($username, $password);

        //Validamos si la consulta retornó datos
        if ($result_validation_user_password != false){

            //$token=ValidateData::generateRandomToken();

            //Validamos y definimos el rol que tendrá el usuario
            if ($result_validation_user_password[0]['rol'] == 'user')
                $rol = 'user';
            else if ($result_validation_user_password[0]['rol'] == 'admin')
                $rol = 'admin';

            // Obtenemos el 'Id_usuario' y el 'Id_empresa'
            $user_id = $result_validation_user_password[0]['id_usuario'];

            //Definimos y obtenemos la variable APC correspondiente a la sesión iniciada
            /*$key_session=constant('PREFIJO_SESSION').'_'.$company_id.'_'.$user_id;
            if (Apc::existApc($key_session)==true){
                $userCredentials = array(
                    'sessionStatus' => '1',
                    'sessionrol' => 'session_iniciada'
                );
                echo json_encode($userCredentials);
                exit();
            }else{
                $login_confirm = Security::sessionCreate($rol, $user_id);
            }*/

            //Creamos y definimos los datos de $_SESSION[]
            $login_confirm = Security::sessionCreate($rol, $user_id);

            //Validamos si se establecieron las variables de Session
            if($login_confirm){

                //Creamos array que contendrá los datos a retornar
                $response = array(
                    'session_status' => '1',
                    'session_rol' => $rol
                );
            }
            else
                $response = array(
                    'session_status' => '0',
                    'session_rol' => 'na'
                );
        }
        else
            $response = array(
                'session_status' => '0',
                'session_rol' => 'na'
            );

        //Retornamos la respuesta en formato Json
        echo json_encode($response);