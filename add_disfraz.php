<?php
require 'db.php';
if (!esAdmin()) { 
  header('Location: index.php'); 
  exit; 
}

$mensaje = '';

if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
  $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
  $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
  $votos = 0;

  $foto_nombre = '';
  $foto_blob = null;

  // Manejo del archivo de imagen
  if (!empty($_FILES['foto']['name'])) {
    $archivo = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $size = $_FILES['foto']['size'];
    $extension = explode('.', $archivo);
    $ext = strtolower(end($extension));
    $allowed = array('jpg','jpeg','png','gif','webp');
    $max_size = 2 * 1024 * 1024; // 2MB

    if ($size > $max_size) {
      $mensaje = 'El archivo supera el tamaño máximo permitido (2MB).';
    } elseif (!in_array($ext, $allowed)) {
      $mensaje = 'Extensión no permitida. Solo: ' . implode(', ', $allowed);
    } elseif (!is_uploaded_file($tmp)) {
      $mensaje = 'No se detectó archivo subido correctamente.';
    } else {
      $info = @getimagesize($tmp);
      if ($info === false) {
        $mensaje = 'El archivo no es una imagen válida.';
      } else {
        if (!file_exists('fotos')) mkdir('fotos', 0777, true);
        $qu = time() . '_' . rand(1000,9999);
        $foto_nombre = $qu . '.' . $ext;
        $destino = 'fotos/' . $foto_nombre;
        if (move_uploaded_file($tmp, $destino)) {
          $foto_blob = mysqli_real_escape_string($con, file_get_contents($destino));
        } else {
          $mensaje = 'Error al guardar la imagen en el servidor.';
        }
      }
    }
  }

  if ($mensaje === '') {
    $foto_nombre_sql = mysqli_real_escape_string($con, $foto_nombre);
    $sql = "INSERT INTO disfraces (nombre, descripcion, votos, foto, foto_blob, eliminado) 
            VALUES ('$nombre', '$descripcion', $votos, '$foto_nombre_sql', '$foto_blob', 0)";
    if (mysqli_query($con, $sql)) {
      header('Location: admin.php'); 
      exit;
    } else {
      $mensaje = 'Error al guardar en la base de datos: ' . mysqli_error($con);
    }
  }
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Agregar disfraz</title>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg">
<div class="container">
  <h1>Agregar nuevo disfraz 🎃</h1>

  <?php if($mensaje): ?>
    <p class="error"><?=htmlspecialchars($mensaje)?></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Nombre</label>
    <input type="text" name="nombre" required>
    <label>Descripción</label>
    <textarea name="descripcion" required></textarea>
    <label>Foto (max 2MB)</label>
    <input type="file" name="foto" accept="image/*" required>
    <button type="submit">Agregar disfraz</button>
  </form>

  <p><a href="admin.php">Volver al panel</a></p>
</div>
</body>
</html>
