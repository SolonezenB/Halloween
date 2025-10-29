<?php
require 'db.php';
if (!esAdmin()) { 
  header('Location: index.php'); 
  exit; 
}

if (!isset($_GET['id'])) { 
  header('Location: admin.php'); 
  exit; 
}

$id = intval($_GET['id']);

// Obtener foto y borrar archivo si existe
$q = "SELECT foto FROM disfraces WHERE id=$id LIMIT 1";
$r = mysqli_query($con, $q); // ✅ Corregido: agregado el signo $

if ($r && mysqli_num_rows($r) == 1) {
  $row = mysqli_fetch_assoc($r);
  if (!empty($row['foto']) && file_exists('fotos/'.$row['foto'])) {
    unlink('fotos/' . $row['foto']);
  }
}

// Borrar votos relacionados (opcional si no hay ON DELETE CASCADE)
mysqli_query($con, "DELETE FROM votos WHERE id_disfraz=$id");

// Borrar el disfraz
mysqli_query($con, "DELETE FROM disfraces WHERE id=$id");

header('Location: admin.php');
exit;
?>
