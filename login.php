<?php
require 'db.php';
$mensaje = '';
if (isset($_POST['nombre']) && isset($_POST['clave'])) {
$nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$clave = $_POST['clave'];


$q = "SELECT id, clave FROM usuarios WHERE nombre='$nombre' LIMIT 1";
$r = mysqli_query($con, $q);
if (mysqli_num_rows($r) == 1) {
$row = mysqli_fetch_assoc($r);
if (password_verify($clave, $row['clave'])) {
$_SESSION['usuario_id'] = $row['id'];
$_SESSION['usuario_nombre'] = $nombre;
header('Location: index.php'); exit;
} else {
$mensaje = 'Clave incorrecta.';
}
} else {
$mensaje = 'Usuario no encontrado.';
}
}
?>


<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Login - Halloween</title>
<link rel="stylesheet" href="css/styles_login.css">
</head>
<body class="bg">
<div class="container">
<h1>Iniciar sesión</h1>
<?php if($mensaje) echo '<p class="error">'.htmlspecialchars($mensaje).'</p>'; ?>
<form method="post">
<label>Nombre de usuario</label>
<input type="text" name="nombre" required>
<label>Clave</label>
<input type="password" name="clave" required>
<button type="submit">Ingresar</button>
</form>
<p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
</div>
</body>
</html>