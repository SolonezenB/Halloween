<?php
require 'db.php';
$mensaje = '';
if (isset($_POST['nombre']) && isset($_POST['clave'])) {
$nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);


// Verificar si ya existe
$q = "SELECT id FROM usuarios WHERE nombre='$nombre'";
$r = mysqli_query($con, $q);
if (mysqli_num_rows($r) > 0) {
$mensaje = 'El nombre de usuario ya existe.';
} else {
$ins = "INSERT INTO usuarios (nombre, clave) VALUES ('$nombre', '$clave')";
if (mysqli_query($con, $ins)) {
$id_nuevo = mysqli_insert_id($con);
$_SESSION['usuario_id'] = $id_nuevo;
$_SESSION['usuario_nombre'] = $nombre;
header('Location: index.php'); exit;
} else {
$mensaje = 'Error al registrar: ' . mysqli_error($con);
}
}
}
?>


<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Registro - Halloween</title>
<link rel="stylesheet" href="css/styles_registro.css">
</head>
<body class="bg">
<div class="container">
<h1>Registro</h1>
<?php if($mensaje) echo '<p class="error">'.htmlspecialchars($mensaje).'</p>'; ?>
<form method="post">
<label>Nombre de usuario</label>
<input type="text" name="nombre" required>
<label>Clave</label>
<input type="password" name="clave" required>
<button type="submit">Registrarse</button>
</form>
<p>¿Ya tienes cuenta? <a href="login.php">Ingresar</a></p>
</div>
</body>
</html>