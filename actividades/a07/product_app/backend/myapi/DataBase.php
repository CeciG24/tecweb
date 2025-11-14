<?php

namespace App;
abstract class DataBase
{
    protected $conexion;

    public function __construct($host, $user, $pass, $dbName)
    {
        $this->conexion = new \mysqli($host, $user, $pass, $dbName);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }
}


?>