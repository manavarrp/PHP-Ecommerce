<?php

//Creacion de la variable de conexion
$servidor = 'mysql:dbname='.BD.';host='.SERVIDOR;

//Se crea la conexion
try {

    $pdo = new PDO($servidor, USUARIO, PASSWORD,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8')
                );


}catch(PDOException $e){



}


?>