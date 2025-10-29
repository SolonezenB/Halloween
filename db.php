<?php
// db.php
session_start();


$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'halloween';


$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$con) {
die('Error en la conexión: ' . mysqli_connect_error());
}


function esAdmin() {
return (isset($_SESSION['usuario_nombre']) && $_SESSION['usuario_nombre'] === 'admin');
}


?>