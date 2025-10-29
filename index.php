<?php
require 'db.php';


// Obtener disfraces no eliminados
$q = "SELECT id, nombre, descripcion, votos, foto FROM disfraces WHERE eliminado=0 ORDER BY votos DESC";
$r = mysqli_query($con, $q);


?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Disfraces - Halloween</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg">
<header>
<h1>Concurso de Disfraces</h1>
<nav>
<?php if(isset($_SESSION['usuario_nombre'])): ?>
<span>Hola, <?=htmlspecialchars($_SESSION['usuario_nombre'])?></span>
<a href="logout.php">Salir</a>
<?php if(esAdmin()): ?> | <a href="admin.php">Admin</a> <?php endif; ?>
<?php else: ?>
<a href="login.php">Ingresar</a> | <a href="registro.php">Registro</a>
<?php endif; ?>
</nav>
</header>


<main class="container">
<?php while($dis = mysqli_fetch_assoc($r)): ?>
<div class="card">
<?php if(!empty($dis['foto']) && file_exists('fotos/'.$dis['foto'])): ?>
<img src="fotos/<?=htmlspecialchars($dis['foto'])?>" alt="<?=htmlspecialchars($dis['nombre'])?>">
<?php endif; ?>
<h2><?=htmlspecialchars($dis['nombre'])?></h2>
<p><?=nl2br(htmlspecialchars($dis['descripcion']))?></p>
<p class="votos">Votos: <?=number_format($dis['votos'],0,',','.')?></p>


<?php if(isset($_SESSION['usuario_id'])): ?>
<form method="post" action="votar.php">
<input type="hidden" name="id_disfraz" value="<?=intval($dis['id'])?>">
<button type="submit">Votar</button>
</form>
<?php else: ?>
<p><a href="login.php">Inicia sesión para votar</a></p>
<?php endif; ?>
</div>
<?php endwhile; ?>
</main>
</body>
</html>