<?php
require 'db.php';
if (!isset($_SESSION['usuario_id'])) {
header('Location: login.php'); exit;
}


if (isset($_POST['id_disfraz'])) {
$id_disfraz = intval($_POST['id_disfraz']);
$id_usuario = intval($_SESSION['usuario_id']);


// Verificamos si ya votó
$q = "SELECT id FROM votos WHERE id_usuario=$id_usuario AND id_disfraz=$id_disfraz LIMIT 1";
$r = mysqli_query($con, $q);
if (mysqli_num_rows($r) > 0) {
// Ya votó
header('Location: index.php'); exit;
}


// Insertar voto
$ins = "INSERT INTO votos (id_usuario, id_disfraz) VALUES ($id_usuario, $id_disfraz)";
if (mysqli_query($con, $ins)) {
// Actualizar contador en disfraces
$up = "UPDATE disfraces SET votos = votos + 1 WHERE id = $id_disfraz";
mysqli_query($con, $up);
} else {
// Manejar error si es necesario
}
}
header('Location: index.php'); exit;