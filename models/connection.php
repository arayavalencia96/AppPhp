<?php 

class Connection {

    static public function conn() {

        $link = new PDO("mysql:host=localhost;dbname=apicursosapplication","root","");
        $link -> exec('set names utf8');

        return $link;

    }

}

?>