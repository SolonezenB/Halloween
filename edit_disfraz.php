<?php
require 'db.php';
if (!esAdmin()) { header('Location: index.php'); exit; }
$mensaje = '';

if (!isset($_GET['id']) && !isset($_POST['id'])) {
  header('Location: admin.php'); exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);

// Si se envió el formulario de edición
if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
  $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
  $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);

  // Manejar posible nueva foto
  $foto_nombre = '';
  $foto_blob = null;
  $subio_foto = false;

  if (!empty($_FILES['foto']) && isset($_FILES['foto']['name']) && $_FILES['foto']['name'] !== '') {
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
        $mensaje = 'El archivo subido no es una imagen válida.';
      } else {
        if (!file_exists('fotos')) mkdir('fotos', 0777, true);
        $qu = time() . '_' . rand(1000,9999);
        $foto_nombre = $qu . '.' . $ext;
        $destino = 'fotos/' . $foto_nombre;
        if (move_uploaded_file($tmp, $destino)) {
          $foto_blob = mysqli_real_escape_string($con, file_get_contents($destino));
          $subio_foto = true;
        } else {
          $mensaje = 'Error al mover el archivo subido.';
        }
      }
    }
  }

  if ($mensaje === '') {
    // Si subió foto: borrar la anterior y actualizar foto+blob
    if ($subio_foto) {
      // obtener foto anterior
      $qold = "SELECT foto FROM disfraces WHERE id=$id LIMIT 1";
      $ro = mysqli_query($con, $qold);
      if ($ro && mysqli_num_rows($ro)==1) {
        $old = mysqli_fetch_assoc($ro);
        if (!empty($old['foto']) && file_exists('fotos/'.$old['foto'])) unlink('fotos/' . $old['foto']);
      }

      $foto_nombre_sql = mysqli_real_escape_string($con, $foto_nombre);
      $upd = "UPDATE disfraces SET nombre='$nombre', descripcion='$descripcion', foto='$foto_nombre_sql', foto_blob='$foto_blob' WHERE id=$id";
    } else {
      $upd = "UPDATE disfraces SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id";
    }

    if (mysqli_query($con, $upd)) {
      header('Location: admin.php'); exit;
    } else {
      $mensaje = 'Error al actualizar: ' . mysqli_error($con);
    }
  }
}

// Obtener disfraz
$q = "SELECT * FROM disfraces WHERE id=$id LIMIT 1";
$r = mysqli_query($con, $q);
if (!$r || mysqli_num_rows($r)==0) { header('Location: admin.php'); exit; }
$dis = mysqli_fetch_assoc($r);
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar disfraz</title>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg">
<div class="container">
  <h1>Editar disfraz</h1>
  <?php if($mensaje) echo '<p class="error">'.htmlspecialchars($mensaje).'</p>'; ?>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?=intval($dis['id'])?>">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?=htmlspecialchars($dis['nombre'])?>" required>
    <label>Descripción</label>
    <textarea name="descripcion" required><?=htmlspecialchars($dis['descripcion'])?></textarea>

    <?php if(!empty($dis['foto']) && file_exists('fotos/'.$dis['foto'])): ?>
      <p>Foto actual:</p>
      <img src="fotos/<?=htmlspecialchars($dis['foto'])?>" style="max-width:200px;">
    <?php endif; ?>

    <label>Subir nueva foto (opcional, max 2MB)</label>
    <input type="file" name="foto" accept="image/*">
    <button type="submit">Guardar cambios</button>
  </form>
  <p><a href="admin.php">Volver al panel</a></p>
</div>
</body>
</html>
