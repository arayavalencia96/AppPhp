<?php 

require_once 'connection.php';

class modelClient {

    static public function index($tabla) {

        $stmt = Connection::conn()->prepare("SELECT * FROM {$tabla}");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt = null;

    }

    static public function createUser($tabla, $datos) {

        $stmt = Connection::conn()->prepare("INSERT INTO clientes(nombre, apellido, email, 
        id_cliente, llave_secreta, created_at, updated_at) VALUES (:nombre, :apellido, :email, 
        :id_cliente, :llave_secreta, :created_at, :updated_at)");

        $stmt -> bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt -> bindParam(':apellido', $datos['apellido'], PDO::PARAM_STR);
        $stmt -> bindParam(':email', $datos['email'], PDO::PARAM_STR);
        $stmt -> bindParam(':id_cliente', $datos['id_cliente'], PDO::PARAM_STR);
        $stmt -> bindParam(':llave_secreta', $datos['llave_secreta'], PDO::PARAM_STR);
        $stmt -> bindParam(':created_at', $datos['created_at'], PDO::PARAM_STR);
        $stmt -> bindParam(':updated_at', $datos['updated_at'], PDO::PARAM_STR);

        if($stmt->execute()) {
            return 'ok';
        } else {
            print_r(Connection::conn()->errorInfo());
        }
        $stmt->close();
        $stmt = null;
    }

    static public function updateUser($tabla, $datos) {

        $stmt = Connection::conn()->prepare("UPDATE clientes SET nombre=:nombre, apellido=:apellido,
        email=:email, updated_at=:updated_at WHERE id=:id");

        $stmt->bindParam(':id', $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $datos['apellido'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $datos['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            print_r(Connection::conn()->errorInfo());
        }
        $stmt->close();
        $stmt = null;

    }

    public static function deleteUser($tabla, $id)
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

    public static function showUser($tabla, $id)
    {

        $stmt = Connection::conn()->prepare("SELECT * FROM $tabla WHERE id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt->close();
        $stmt = null;

    }

};

?>