<?php

$host = "127.7.0.1";
$user = "root";
$pass = "root";
$name = "gestor_de_tareas_basico";
$port = 3307;

$conexion = mysqli_connect($host, $user, $pass, $name, $port);

if(!$conexion){
    die('Error al conectarse a la base de datos ' . mysqli_connect_error());
}