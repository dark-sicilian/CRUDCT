<?php

$servidor="mysql:dbname=crud_;host=127.0.0.1;";
$usuario="root";
$password="";


try{
    $pdo=New PDO($servidor,$usuario,$password, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
    //echo "Conectado..";
    
}catch(PDOException $e){

    echo "Conexion MAMASTROSA :(".$e->getMessage();

}