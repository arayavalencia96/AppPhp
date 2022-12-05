<?php

class ControladorCursos
{
    public function index($page)
    {

        $clientes = modelClient::index('clientes');

        // Validar credenciales del cliente
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            foreach ($clientes as $key => $value) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($value['id_cliente'] . ':' . $value['llave_secreta'])) {

                        if ($page != null) {
                            $cant = 10;
                            $from = ($page-1) * $cant;

                            $cursos = modelCourse::index('cursos', 'clientes', $cant, $from);
                        } else {
                            $cursos = modelCourse::index('cursos', 'clientes', null, null);
                        }

                    $json = array(
                        'detalle' => $cursos,
                    );
                    echo json_encode($json, true);
                    return;

                }
            }
        }
    }

    public function create($datos)
    {

        $clientes = modelClient::index('clientes');

        foreach ($clientes as $key => $valueCliente) {

            if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {

                // Validando los datos
                foreach ($datos as $key => $valueDatos) {
                    if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\<\\>\\?\\¿
                    \\!\\¡\\:\\,\\.\\0-9a-z-A-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                        $json = array(
                            'status' => 404,
                            'detalle' => 'Error en el campo ' . $key);
                        echo json_encode($json, true);
                        return;
                    }
                }

                // Validando que el título y la descripción no estén repetidas
                $cursos = modelCourse::index('cursos', 'clientes', null, null);
                foreach ($cursos as $key => $value) {
                    if ($value->titulo == $datos['titulo'] || $value->descripcion == $datos['descripcion']) {
                        $json = array(
                            'status' => 404,
                            'detalle' => 'Título y/o descripción existentes en base de datos.');
                        echo json_encode($json, true);
                        return;
                    };
                };
            }
        }

        // Levando los datos al cursos.model
        $datos = array('titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'instructor' => $datos['instructor'],
            'imagen' => $datos['imagen'],
            'precio' => $datos['precio'],
            'id_creador' => $valueCliente['id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'));

        $create = modelCourse::createCourse('cursos', $datos);

        if ($create == 'ok') {
            $json = array(
                'status' => 200,
                'detalle' => 'Se creó correctamente el curso.');
            echo json_encode($json, true);
            return;
        }

    }

    public function show($id)
    {

        $clientes = modelClient::index('clientes');

        foreach ($clientes as $key => $valueCliente) {

            if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {

                $curso = modelCourse::show('cursos', 'clientes', $id);
                /* echo '<pre>'; print_r($curso); echo '</pre>'; */
                if (!empty($curso)) {
                    $json = array(
                        'status' => 200,
                        'detalle' => $curso,
                    );
                    echo json_encode($json, true);
                    return;
                } else {
                    $json = array(
                        'status' => 200,
                        'total_registros' => 0,
                        'detalle' => 'No hay ningún curso registrado con este id.',
                    );
                    echo json_encode($json, true);
                    return;
                }
            }
        }

    }

    public function updateCourse($id, $datos)
    {
        $clientes = modelClient::index('clientes');

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($clientes as $key => $valueCliente) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {
    
                    // Validando los datos
                    foreach ($datos as $key => $valueDatos) {
                        if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\<\\>\\?\\¿
                        \\!\\¡\\:\\,\\.\\0-9a-z-A-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                            $json = array(
                                'status' => 404,
                                'detalle' => 'Error en el campo ' . $key);
                            echo json_encode($json, true);
                            return;
                        }
                    }
    
                    // Validando el id
                    $course = modelCourse::show('cursos', 'clientes', $id);
    
                    foreach ($course as $key => $valueCourse) {
    
                        if ($valueCourse->id_creador == $valueCliente['id']) {
                            // Levando los datos al cursos.model
                            $datos = array('id' => $id,
                                'titulo' => $datos['titulo'],
                                'descripcion' => $datos['descripcion'],
                                'instructor' => $datos['instructor'],
                                'imagen' => $datos['imagen'],
                                'precio' => $datos['precio'],
                                'updated_at' => date('Y-m-d H:i:s'));
    
                            $update = modelCourse::updateCourse('cursos', $datos);
    
                            if ($update == 'ok') {
                                $json = array(
                                    'status' => 200,
                                    'detalle' => 'Se actualizó correctamente el curso.');
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

    public function deleteCourse($id)
    {

        $clientes = modelClient::index('clientes');

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($clientes as $key => $valueCliente) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueCliente['id_cliente'] . ':' . $valueCliente['llave_secreta'])) {
    
                    // Validando el id
                    $course = modelCourse::show('cursos', 'clientes', $id);
    
                    foreach ($course as $key => $valueCourse) {
    
                        if ($valueCourse->id_creador == $valueCliente['id']) {
    
                            $delete = modelCourse::deleteCourse('cursos', $id);
    
                            if ($delete == 'ok') {
                                $json = array(
                                    'status' => 200,
                                    'detalle' => 'Se eliminó correctamente el curso.');
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
};
