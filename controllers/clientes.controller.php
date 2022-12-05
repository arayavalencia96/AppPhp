<?php

class ControladorClientes
{

    public function createUser($datos)
    {

        // Validar nombre
        if (isset($datos['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['nombre'])) {

            $json = array(
                'status' => 404,
                'detalle' => 'error - campo nombre permite solo letras',
            );

            echo json_encode($json, true);

            return;

        }

        // Validar apellido
        if (isset($datos['apellido']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['apellido'])) {

            $json = array(
                'status' => 404,
                'detalle' => 'error - campo apellido permite solo letras',
            );

            echo json_encode($json, true);

            return;

        }

        // Validar email
        if (isset($datos['email']) && !preg_match('/^[^0-9][a-zA0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.]
        [a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos['email'])) {

            $json = array(
                'status' => 404,
                'detalle' => 'error - campo email invalido',
            );

            echo json_encode($json, true);

            return;

        }

        // Validar email repetido

        $clientes = modelClient::index('clientes');

        foreach ($clientes as $key => $value) {
            if ($value['email'] == $datos['email']) {
                $json = array(
                    'status' => 404,
                    'detalle' => 'error - campo email repetido',
                );
                echo json_encode($json, true);
                return;
            }
        }

        // Generar credenciales del cliente
        $id_cliente = str_replace('$', 'i', crypt($datos['nombre'] . $datos['apellido'] . $datos['email'],
            '$2a$07$afartwetsdAD52356FEDGsfhsd$'));

        $llave_secreta = str_replace('$', 'k', crypt($datos['email'] . $datos['apellido'] . $datos['nombre'],
            '$2a$07$afartwetsdAD52356FEDGsfhsd$'));

        $datos = array('nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'email' => $datos['email'],
            'id_cliente' => $id_cliente,
            'llave_secreta' => $llave_secreta,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'));

        $createUser = modelClient::createUser('clientes', $datos);

        if ($createUser == 'ok') {
            $json = array(
                'status' => 404,
                'detalle' => 'se genero correctamente',
                'id_cliente' => $id_cliente,
                'llave_secreta' => $llave_secreta);
            echo json_encode($json, true);
            return;
        }

    }

    public function updateUser($id, $datos)
    {
        $clientes = modelClient::index('clientes');

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($clientes as $key => $valueCliente) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {

                    // Validar nombre
                    if (isset($datos['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['nombre'])) {
                        $json = array(
                            'status' => 404,
                            'detalle' => 'error - campo nombre permite solo letras',
                        );
                        echo json_encode($json, true);
                        return;
                    }

                    // Validar apellido
                    if (isset($datos['apellido']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos['apellido'])) {
                        $json = array(
                            'status' => 404,
                            'detalle' => 'error - campo apellido permite solo letras',
                        );
                        echo json_encode($json, true);
                        return;
                    }

                    /* // Validar email
                    if (isset($datos['email']) && !preg_match('/^[^0-9][a-zA0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos['email'])) {
                        $json = array(
                            'status' => 404,
                            'detalle' => 'error - campo email invalido',
                        );
                        echo json_encode($json, true);
                        return;
                    } */

                    // Validando el id
                    $cliente = modelClient::showUser('clientes', $id);

                    foreach ($cliente as $key => $valueClient) {

                        if ($valueClient->id == $valueCliente['id']) {
                            // Levando los datos al cursos.model
                            $datos = array('id' => $id,
                                'nombre' => $datos['nombre'],
                                'apellido' => $datos['apellido'],
                                'email' => $datos['email'],
                                'updated_at' => date('Y-m-d H:i:s'));

                            $update = modelClient::updateUser('clientes', $datos);

                            if ($update == 'ok') {
                                $json = array(
                                    'status' => 200,
                                    'detalle' => 'Se actualizó correctamente el user.');
                                echo json_encode($json, true);
                                return;
                            } else {
                                $json = array(
                                    'status' => 404,
                                    'detalle' => 'No autorizado para realizar este cambio.');
                                echo json_encode($json, true);
                                return;
                            }
                        } else {
                            $json = array(
                                'status' => 404,
                                'detalle' => 'No autorizado para realizar este cambio.');
                            echo json_encode($json, true);
                            return;
                        }
                    }
                }
            }
        }
    }

    public function deleteUser($id)
    {

        $clientes = modelClient::index('clientes');

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($clientes as $key => $valueCliente) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {

                    // Validando el id
                    $cliente = modelClient::showUser('clientes', $id);

                    foreach ($cliente as $key => $valueCourse) {

                        $delete = modelClient::deleteUser('clientes', $id);

                            if ($delete == 'ok') {
                                $json = array(
                                    'status' => 200,
                                    'detalle' => 'Se eliminó correctamente el user.');
                                echo json_encode($json, true);
                                return;
                            } else {
                                $json = array(
                                    'status' => 404,
                                    'detalle' => 'No autorizado para realizar este cambio.');
                                echo json_encode($json, true);
                                return;
                            }
                    }
                }
            }
        }

    }

    public function showUser($id)
    {

        $clientes = modelClient::index('clientes');

        foreach ($clientes as $key => $valueCliente) {

            if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {

                $cliente = modelClient::showUser('clientes', $id);
                /* echo '<pre>'; print_r($curso); echo '</pre>'; */
                if (!empty($cliente)) {
                    $json = array(
                        'status' => 200,
                        'detalle' => $cliente,
                    );
                    echo json_encode($json, true);
                    return;
                } else {
                    $json = array(
                        'status' => 200,
                        'total_registros' => 0,
                        'detalle' => 'No hay ningún user registrado con este id.',
                    );
                    echo json_encode($json, true);
                    return;
                }
            }
        }

    }

    public function index()
    {

        $clientes = modelClient::index('clientes');

        // Validar credenciales del cliente
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            foreach ($clientes as $key => $value) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($value['id_cliente'] . ':' . $value['llave_secreta'])) {

                        $clientes = modelClient::index('clientes');

                    $json = array(
                        'detalle' => $clientes,
                    );
                    echo json_encode($json, true);
                    return;

                }
            }
        }
    }

};
