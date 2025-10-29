<?php
require 'db.php';
if (!esAdmin()) { header('Location: index.php'); exit; }

$q = "SELECT * FROM disfraces ORDER BY id DESC";
$r = mysqli_query($con, $q);
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Panel de Administración</title>
<link rel="stylesheet" href="css/styles_disfraz.css">
</head>
<body class="bg">
<div class="container">
  <h1>🎃 Panel de administración</h1>
  <p><a href="logout.php">Cerrar sesión</a></p>

  <h2>Agregar nuevo disfraz</h2>
  <form action="add_disfraz.php" method="post" enctype="multipart/form-data">
    <label>Nombre</label>
    <input type="text" name="nombre" required>
    <label>Descripción</label>
    <textarea name="descripcion" required></textarea>
    <label>Subir foto (max 2MB)</label>
    <input type="file" name="foto" accept="image/*" required>
    <button type="submit">Agregar</button>
  </form>

  <h2>Lista de disfraces</h2>
  <table border="1" cellpadding="6" cellspacing="0" style="width:100%;background:#222;color:#eee;">
    <tr style="background:#000;color:#ff7518;">
      <th>ID</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Foto</th>
      <th>Votos</th>
      <th>Acciones</th>
    </tr>
    <?php while ($d = mysqli_fetch_assoc($r)): ?>
      <tr>
        <td><?=intval($d['id'])?></td>
        <td><?=htmlspecialchars($d['nombre'])?></td>
        <td><?=htmlspecialchars($d['descripcion'])?></td>
        <td>
          <?php if(!empty($d['foto']) && file_exists('fotos/'.$d['foto'])): ?>
            <img src="fotos/<?=htmlspecialchars($d['foto'])?>" width="80">
          <?php else: ?>
            Sin foto
          <?php endif; ?>
        </td>
        <td><?=intval($d['votos'])?></td>
        <td>
          <a href="edit_disfraz.php?id=<?=$d['id']?>">✏️ Editar</a> |
          <a href="delete_disfraz.php?id=<?=$d['id']?>" onclick="return confirm('¿Seguro que deseas eliminar este disfraz? 🩸')">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
