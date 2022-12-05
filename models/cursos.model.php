<?php

require_once 'connection.php';

class modelCourse
{

    public static function index($tabla, $tabla1, $cant, $from)
    {

        if ($cant != null) {
            $stmt = Connection::conn()->prepare("SELECT cursos.id, cursos.titulo, cursos.descripcion,
            cursos.instructor, cursos.imagen, cursos.precio, cursos.id_creador, clientes.nombre,
            clientes.apellido FROM {$tabla} INNER JOIN {$tabla1} ON cursos.id_creador = clientes.id
            LIMIT $from, $cant");
        } else {
            $stmt = Connection::conn()->prepare("SELECT cursos.id, cursos.titulo, cursos.descripcion,
            cursos.instructor, cursos.imagen, cursos.precio, cursos.id_creador, clientes.nombre,
            clientes.apellido FROM {$tabla} INNER JOIN {$tabla1} ON cursos.id_creador = clientes.id");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt->close();
        $stmt = null;

    }

    public static function createCourse($tabla, $datos)
    {

        $stmt = Connection::conn()->prepare("INSERT INTO $tabla(titulo, descripcion, instructor, imagen,
        precio, id_creador, created_at, updated_at) VALUES (:titulo, :descripcion, :instructor, :imagen,
        :precio, :id_creador, :created_at, :updated_at)");

        $stmt->bindParam(':titulo', $datos['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(':instructor', $datos['instructor'], PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $datos['imagen'], PDO::PARAM_STR);
        $stmt->bindParam(':precio', $datos['precio'], PDO::PARAM_STR);
        $stmt->bindParam(':id_creador', $datos['id_creador'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindParam(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            print_r(Connection::conn()->errorInfo());
        }
        $stmt->close();
        $stmt = null;

    }

    public static function show($tabla, $tabla1, $id)
    {

        $stmt = Connection::conn()->prepare("SELECT cursos.id, cursos.titulo, cursos.descripcion,
        cursos.instructor, cursos.imagen, cursos.precio, cursos.id_creador, clientes.nombre,
        clientes.apellido FROM {$tabla} INNER JOIN {$tabla1} ON cursos.id_creador = clientes.id
        WHERE cursos.id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt->close();
        $stmt = null;

    }

    public static function updateCourse($tabla, $datos)
    {

        $stmt = Connection::conn()->prepare("UPDATE cursos SET titulo=:titulo, descripcion=:descripcion,
        instructor=:instructor, imagen=:imagen, precio=:precio, updated_at=:updated_at WHERE id=:id");

        $stmt->bindParam(':id', $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $datos['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(':instructor', $datos['instructor'], PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $datos['imagen'], PDO::PARAM_STR);
        $stmt->bindParam(':precio', $datos['precio'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $datos['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            print_r(Connection::conn()->errorInfo());
        }
        $stmt->close();
        $stmt = null;

    }

    public static function deleteCourse($tabla, $id)
    {

        $stmt = Connection::conn()->prepare("DELETE FROM $tabla WHERE id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            print_r(Connection::conn()->errorInfo());
        }
        $stmt->close();
        $stmt = null;

    }

}
