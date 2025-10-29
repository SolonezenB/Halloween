<?php
require 'db.php';
if (isset($_POST['crear'])) {
$nombre = 'admin';
if (!empty($_POST['nombre'])) $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$clave_plain = 'admin123';
if (!empty($_POST['clave'])) $clave_plain = $_POST['clave'];
$clave = password_hash($clave_plain, PASSWORD_DEFAULT);


$q = "SELECT id FROM usuarios WHERE nombre='" . mysqli_real_escape_string($con,$nombre) . "' LIMIT 1";
$r = mysqli_query($con, $q);
if (mysqli_num_rows($r) > 0) {
$msg = 'El usuario ya existe.';
} else {
$ins = "INSERT INTO usuarios (nombre, clave) VALUES ('" . mysqli_real_escape_string($con,$nombre) . "', '" . mysqli_real_escape_string($con,$clave) . "')";
if (mysqli_query($con, $ins)) {
$msg = 'Administrador creado correctamente. Recuerda eliminar este archivo por seguridad.';
} else {
$msg = 'Error al crear admin: ' . mysqli_error($con);
}
}
}
?>


<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Crear Admin</title>
<link rel="stylesheet" href="styles.css">
</head>
<body class="bg">
<div class="container">
<h1>Crear usuario administrador</h1>
<?php if(isset($msg)) echo '<p class="error">'.htmlspecialchars($msg).'</p>'; ?>
<form method="post">
<label>Nombre (por defecto 'admin')</label>
<input type="text" name="nombre" placeholder="admin">
<label>Clave (por defecto 'admin123')</label>
<input type="text" name="clave" placeholder="admin123">
<button type="submit" name="crear">Crear administrador</button>
</form>
<p><strong>Importante:</strong> Una vez creado el admin, borra este archivo `crear_admin.php` o restríngele el acceso.</p>
</div>
</body>
</html>